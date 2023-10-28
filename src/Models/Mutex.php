<?php

namespace SMSkin\LaravelSupport\Models;

use Illuminate\Support\Facades\Cache;

class Mutex
{
    public const CACHE_TAG = 'mutex';

    public function __construct(protected string $key, protected int $ttl)
    {
    }

    public function unlock(): void
    {
        $facade = Cache::getFacadeRoot();
        if (Cache::supportsTags()) {
            $facade = Cache::tags([Mutex::CACHE_TAG]);
        }
        $facade->forget(md5($this->key));
    }
}