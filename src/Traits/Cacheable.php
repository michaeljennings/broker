<?php

namespace Michaeljennings\Broker\Traits;

use Michaeljennings\Broker\Observer;

trait Cacheable
{
    /**
     * Boot the trait.
     */
    public static function bootCacheable()
    {
        static::observe(Observer::class);
    }

    /**
     * Get the key to cache the attributes against.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->getTable();
    }

    /**
     * Clear the cache for this cacheable entity.
     */
    public function flushCache()
    {
        return broker()->flush($this);
    }

    /**
     * Clear all of the cache entities associated with this cacheable type.
     */
    public function flushAll()
    {
        return broker()->flushAll(get_class($this));
    }
}