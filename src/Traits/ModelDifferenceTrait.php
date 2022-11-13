<?php

namespace SMSkin\LaravelSupport\Traits;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;
use SMSkin\LaravelSupport\Models\ModelField;
use Illuminate\Support\Collection;

trait ModelDifferenceTrait
{
    protected Model|null $lockedState = null;

    protected array $differenceIgnoredFields = [
        'created_at',
        'updated_at'
    ];

    public function lockModelState(): void
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->lockedState = clone $this;
    }

    /**
     * @return Collection<ModelField>
     */
    public function getChangedFields(): Collection
    {
        if (!$this->lockedState) {
            throw new RuntimeException('You need to lock the state before this action');
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $currentObjectAttributes = $this->attributesToArray();
        $changedFields = collect();
        foreach ($this->lockedState->attributesToArray() as $field => $value) {
            if (in_array($field, $this->differenceIgnoredFields)) {
                continue;
            }

            $newValue = $currentObjectAttributes[$field];
            if ($value == $newValue) {
                continue;
            }

            $changedFields->push(
                (new ModelField)
                    ->setField($field)
                    ->setOldValue($value)
                    ->setNewValue($newValue)
            );
        }
        $this->lockedState = null;
        return $changedFields;
    }
}