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
        Cache::tags([self::CACHE_TAG])->forget(md5($this->key));
    }
}