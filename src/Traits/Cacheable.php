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
        if ( ! is_null(static::$dispatcher)) {
            static::observe(new Observer(static::$dispatcher));
        }
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
}