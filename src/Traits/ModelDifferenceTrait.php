<?php

namespace SMSkin\LaravelSupport\Traits;

use BackedEnum;
use Carbon\CarbonInterval;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SMSkin\LaravelSupport\Models\ModelDifferenceOptions;
use SMSkin\LaravelSupport\Models\ModelField;

trait ModelDifferenceTrait
{
    protected array $md = ['original' => [], 'difference' => []];

    /** @noinspection PhpUnused */
    protected static function bootModelDifferenceTrait(): void
    {
        static::updating(static function (Model $model) {
            $oldValues = (new static())->setRawAttributes($model->getRawOriginal());
            /** @noinspection PhpUndefinedFieldInspection */
            $model->md['original'] = static::dumpModelAttributes($oldValues);
        });

        static::updated(static function (Model $model) {
            /** @noinspection PhpUndefinedFieldInspection */
            /** @noinspection PhpUndefinedMethodInspection */
            $model->md['difference'] = $model->detectModelDifference();
        });
    }

    public function lockModelState(): void
    {
        // deprecated
    }

    public static function getModelDifferenceOptions(): ModelDifferenceOptions
    {
        return ModelDifferenceOptions::defaults()
            ->logExcept([
                'updated_at',
                'created_at'
            ]);
    }

    public function getModelDifference(): array
    {
        return $this->md['difference'];
    }

    /**
     * @return Collection<ModelField>
     */
    public function getChangedFields(): Collection
    {
        $data = [];
        $diff = $this->getModelDifference();
        if (!count($diff)) {
            return collect();
        }
        foreach ($diff['new'] as $field => $value) {
            $original = $diff['original'][$field] ?? null;
            $data[] = (new ModelField)
                ->setField($field)
                ->setOldValue($original)
                ->setNewValue($value);
        }
        return collect($data);
    }

    /**
     * Determines what attributes needs to be logged based on the configuration.
     *
     * @noinspection PhpUnused
     */
    public function attributesToBeLogged(): array
    {
        $options = $this->getModelDifferenceOptions();

        $attributes = array_keys($this->getAttributes());
        if ($options->logExceptAttributes) {

            // Filter out the attributes defined in ignoredAttributes out of the local array
            $attributes = array_diff($attributes, $options->logExceptAttributes);
        }

        return $attributes;
    }

    /**
     * Determines values that will be logged based on the difference.
     *
     * @noinspection PhpUnused
     */
    public function detectModelDifference(): array
    {
        $properties['new'] = static::dumpModelAttributes($this->fresh());
        // Fill the attributes with null values.
        $nullProperties = array_fill_keys(array_keys($properties['new']), null);

        // Populate the old key with keys from database and from old attributes.
        $properties['original'] = array_merge($nullProperties, $this->md['original']);

        // Fail safe.
        unset($this->md['original']);

        // Get difference between the old and new attributes.
        $properties['new'] = array_udiff_assoc(
            $properties['new'],
            $properties['original'],
            static function ($new, $old) {
                // Strict check for php's weird behaviors
                if ($old === null || $new === null) {
                    if ($new === $old) {
                        return 0;
                    }
                    return 1;
                }

                // Handles Date interval comparisons since php cannot use spaceship
                // Operator to compare them and will throw ErrorException.
                if ($old instanceof DateInterval) {
                    if (CarbonInterval::make($old)->equalTo($new)) {
                        return 0;
                    }
                    return 1;
                } elseif ($new instanceof DateInterval) {
                    if (CarbonInterval::make($new)->equalTo($old)) {
                        return 0;
                    }
                    return 1;
                }

                return $new <=> $old;
            }
        );

        $properties['original'] = collect($properties['original'])
            ->only(array_keys($properties['new']))
            ->all();

        return $properties;
    }

    public static function dumpModelAttributes(Model $model): array
    {
        $changes = [];
        /** @noinspection PhpUndefinedMethodInspection */
        $attributes = $model->attributesToBeLogged();

        foreach ($attributes as $attribute) {
            if (Str::contains($attribute, '.')) {
                $changes += self::getRelatedModelAttributeValue($model, $attribute);

                continue;
            }

            if (Str::contains($attribute, '->')) {
                Arr::set(
                    $changes,
                    str_replace('->', '.', $attribute),
                    static::getModelAttributeJsonValue($model, $attribute)
                );

                continue;
            }

            $changes[$attribute] = in_array($attribute, static::getModelDifferenceOptions()->attributeRawValues)
                ? $model->getAttributeFromArray($attribute)
                : $model->getAttribute($attribute);

            if ($changes[$attribute] === null) {
                continue;
            }

            if ($model->isDateAttribute($attribute)) {
                $changes[$attribute] = $model->serializeDate(
                    $model->asDateTime($changes[$attribute])
                );
            }

            if ($model->hasCast($attribute)) {
                $cast = $model->getCasts()[$attribute];

                if (function_exists('enum_exists') && enum_exists($cast)) {
                    if (method_exists($model, 'getStorableEnumValue')) {
                        $changes[$attribute] = $model->getStorableEnumValue($changes[$attribute]);
                    } else {
                        // ToDo: DEPRECATED - only here for Laravel 8 support
                        $changes[$attribute] = $changes[$attribute] instanceof BackedEnum
                            ? $changes[$attribute]->value
                            : $changes[$attribute]->name;
                    }
                }

                if ($model->isCustomDateTimeCast($cast) || $model->isImmutableCustomDateTimeCast($cast)) {
                    $changes[$attribute] = $model->asDateTime($changes[$attribute])->format(explode(':', $cast, 2)[1]);
                }
            }
        }

        return $changes;
    }

    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        $relatedModelNames = explode('.', $attribute);
        $relatedAttribute = array_pop($relatedModelNames);

        $attributeName = [];
        $relatedModel = $model;

        do {
            $attributeName[] = $relatedModelName = static::getRelatedModelRelationName($relatedModel, array_shift($relatedModelNames));

            $relatedModel = $relatedModel->$relatedModelName ?? $relatedModel->$relatedModelName();
        } while (!empty($relatedModelNames));

        $attributeName[] = $relatedAttribute;

        return [implode('.', $attributeName) => $relatedModel->$relatedAttribute ?? null];
    }

    protected static function getRelatedModelRelationName(Model $model, string $relation): string
    {
        return Arr::first([
            $relation,
            Str::snake($relation),
            Str::camel($relation),
        ], static function (string $method) use ($model): bool {
            return method_exists($model, $method);
        }, $relation);
    }

    protected static function getModelAttributeJsonValue(Model $model, string $attribute): mixed
    {
        $path = explode('->', $attribute);
        $modelAttribute = array_shift($path);
        $modelAttribute = collect($model->getAttribute($modelAttribute));

        return data_get($modelAttribute, implode('.', $path));
    }
}