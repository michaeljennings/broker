<?php

namespace Michaeljennings\Broker\Contracts;

interface Broker
{
    /**
     * Store an item in the cache for the referee.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param mixed     $value
     * @param int       $minutes
     * @return mixed
     */
    public function put(Cacheable $cacheable, $key, $value, $minutes = 60);

    /**
     * Store an item in the cache for the referee.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param mixed     $value
     * @return mixed
     */
    public function forever(Cacheable $cacheable, $key, $value);

    /**
     * Store an item in the cache for the referee.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param \Closure  $callback
     * @param int       $minutes
     * @return mixed
     */
    public function remember(Cacheable $cacheable, $key, \Closure $callback, $minutes = 60);

    /**
     * Get a value from the cache.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @return mixed
     */
    public function get(Cacheable $cacheable, $key);

    /**
     * Check if a value exists in the cache.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @return mixed
     */
    public function has(Cacheable $cacheable, $key);

    /**
     * Remove an item or multiple items from the referee's cache.
     *
     * @param Cacheable    $cacheable
     * @param string|array $keys
     * @return bool
     */
    public function forget(Cacheable $cacheable, $keys);

    /**
     * Remove all of the referee's cached items.
     *
     * @param Cacheable $cacheable
     * @return void
     */
    public function flush(Cacheable $cacheable);

    /**
     * Flush all of the cache stored for a cacheable type, not just the
     * entity itself.
     *
     * @param Cacheable|string $cacheable
     * @return void
     */
    public function flushAll($cacheable);

    /**
     * Flush all of the provided tags from the cache. This can be useful if
     * you want to remove all of one type, not just where it is associated
     * with a cacheable item.
     *
     * i.e. The sidebar items are cached against the user's and you want
     * to remove all of the sidebar items.
     *
     * @param array|string $tags
     * @return mixed
     */
    public function flushTags($tags);
}