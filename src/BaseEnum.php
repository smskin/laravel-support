<?php

namespace SMSkin\LaravelSupport;

use SMSkin\LaravelSupport\Models\EnumItem;
use Illuminate\Support\Collection;

abstract class BaseEnum
{
    /**
     * @return Collection<EnumItem>
     */
    public static function items(): Collection
    {
        return collect([]);
    }

    /**
     * @return string[]
     */
    public static function getKeys(): array
    {
        return static::items()->pluck('id')->toArray();
    }

    public static function toNovaSelectorOptions(): array
    {
        return static::items()->mapWithKeys(function (EnumItem $item) {
            return [$item->id => $item->title];
        })->toArray();
    }

    public static function getById(string $id): EnumItem
    {
        return static::items()->filter(function (EnumItem $item) use ($id) {
            return $item->id == $id;
        })->firstOrFail();
    }
}
