<?php

namespace SMSkin\LaravelSupport\Models;

use Illuminate\Support\Facades\Cache;

class Mutex
{
    public function __construct(protected string $key, protected int $ttl)
    {
    }

    public function unlock(): void
    {
        Cache::forget(md5($this->key));
    }
}