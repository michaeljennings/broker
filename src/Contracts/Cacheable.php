<?php

namespace Michaeljennings\Broker\Contracts;

interface Cacheable
{
    /**
     * Get the key to cache the attributes against.
     *
     * @return string
     */
    public function getCacheKey();

    /**
     * Get the unique key for the cacheable item.
     *
     * @return int|string
     */
    public function getKey();
}