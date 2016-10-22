<?php namespace Soda\Cms\Database\Support\Repositories;

abstract class AbstractCacheRepository
{
    protected function cache($key, $ttl, \Closure $closure, $forgetNull = true)
    {
        if ($ttl === true) {
            $value = $this->getCacheStore()->rememberForever($key, $closure);
            if ($value === null && $forgetNull) {
                $this->getCacheStore()->forget($key);
            }

            return $value;
        } elseif (is_int($ttl)) {
            $value = $this->getCacheStore()->remember($key, is_int($ttl) ? $ttl : config('soda.cache.default-ttl'), $closure);
            if ($value === null && $forgetNull) {
                $this->getCacheStore()->forget($key);
            }

            return $value;
        }

        return $closure();
    }

    protected function forget($key)
    {
        return $this->getCacheStore()->forget($key);
    }

    protected function getCacheStore()
    {
        return app('cache');
    }
}
