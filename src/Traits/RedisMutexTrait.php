<?php

namespace SMSkin\LaravelSupport\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use SMSkin\LaravelSupport\Exceptions\MutexException;
use SMSkin\LaravelSupport\Models\Mutex;

trait RedisMutexTrait
{
    /**
     * @throws MutexException
     */
    public function createMutex(string $key, int|null $ttl = null): Mutex
    {
        $ttl ??= 600;
        $hash = md5($key);
        if (Cache::tags([Mutex::CACHE_TAG])->has($hash)) {
            throw new MutexException($key, 0);
        }
        $mutex = new Mutex($key, $ttl);
        Cache::tags([Mutex::CACHE_TAG])->put($hash, '1', now()->addSeconds($ttl));
        return $mutex;
    }

    protected function getMutexKeyByModel(Model $model): string
    {
        return implode('_', [
            get_class($model),
            $model->getKey()
        ]);
    }
}