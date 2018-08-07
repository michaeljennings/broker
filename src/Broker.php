<?php

namespace Michaeljennings\Broker;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Michaeljennings\Broker\Contracts\Broker as BrokerContract;
use Michaeljennings\Broker\Contracts\Cacheable;
use Michaeljennings\Broker\Events\CacheableFlushed;
use Michaeljennings\Broker\Events\CacheableKeyForgotten;
use Michaeljennings\Broker\Events\CacheableKeyWritten;

class Broker implements BrokerContract
{
    /**
     * The laravel cache repository.
     *
     * @var Repository
     */
    protected $cache;

    /**
     * The event dispatcher implementation.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * Broker construct.
     *
     * @param Repository $cache
     */
    public function __construct(Repository $cache, Dispatcher $dispatcher)
    {
        $this->cache = $cache;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Store an item in the cache against the cacheable item.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param mixed     $value
     * @param int       $minutes
     * @return mixed
     */
    public function put(Cacheable $cacheable, $key, $value, $minutes = 60)
    {
        $value = $this->cache->tags($this->getTags($cacheable))->put($key, $value, $minutes);

        $this->event(new CacheableKeyWritten($cacheable, $key, $minutes));

        return $value;
    }

    /**
     * Store an item in the cache against the cacheable item forever.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param mixed     $value
     * @return mixed
     */
    public function forever(Cacheable $cacheable, $key, $value)
    {
        $value = $this->cache->tags($this->getTags($cacheable))->forever($key, $value);

        $this->event(new CacheableKeyWritten($cacheable, $key, 0));

        return $value;
    }

    /**
     * If the keys doesn't exist against the cacheable item, run the provided
     * closure.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param Closure  $callback
     * @param int       $minutes
     * @return mixed
     */
    public function remember(Cacheable $cacheable, $key, Closure $callback, $minutes = 60)
    {
        $callback = function() use ($cacheable, $key, $callback, $minutes) {
            $this->event(new CacheableKeyWritten($cacheable, $key, $minutes));

            return $callback();
        };

        return $this->cache->tags($this->getTags($cacheable))->remember($key, $minutes, $callback);
    }

    /**
     * Retrieve an item from the cache for the cacheable item.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @return mixed
     */
    public function get(Cacheable $cacheable, $key)
    {
        return $this->cache->tags($this->getTags($cacheable))->get($key);
    }

    /**
     * Check if the cacheable item has an item in the cache.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @return mixed
     */
    public function has(Cacheable $cacheable, $key)
    {
        return $this->cache->tags($this->getTags($cacheable))->has($key);
    }

    /**
     * Remove an item or multiple items from the cacheable item's cache.
     *
     * @param Cacheable    $cacheable
     * @param string|array $keys
     * @return bool
     */
    public function forget(Cacheable $cacheable, $keys)
    {
        if ( ! is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            $this->cache->tags($this->getTags($cacheable))->forget($key);
        }

        $this->event(new CacheableKeyForgotten($cacheable, $keys));

        return true;
    }

    /**
     * Remove all of the cacheable item's cached items.
     *
     * @param Cacheable $cacheable
     * @return void
     */
    public function flush(Cacheable $cacheable)
    {
        $this->flushTags($this->relativeKey($cacheable));

        $this->event(new CacheableFlushed($cacheable));
    }

    /**
     * Flush all of the cache stored for a cacheable type, not just the
     * entity itself.
     *
     * @param Cacheable|string $cacheable
     * @return void
     */
    public function flushAll($cacheable)
    {
        if (is_string($cacheable)) {
            $cacheable = new $cacheable;
        }

        $this->flushTags($cacheable->getCacheKey());
    }

    /**
     * Flush all of the provided keys from the cache. This can be useful if
     * you want to remove all of one type, not just where it is associated
     * with a cacheable item.
     *
     * i.e. The sidebar items are cached against the user's and you want
     * to remove all of the sidebar items.
     *
     * @param array|string $tags
     * @return void
     */
    public function flushTags($tags)
    {
        if ( ! is_array($tags)) {
            $tags = func_get_args();
        }

        $this->cache->tags($tags)->flush();
    }

    /**
     * Get the tags associated with the referee.
     *
     * @param Cacheable $cacheable
     * @return array
     */
    protected function getTags(Cacheable $cacheable)
    {
        return [$cacheable->getCacheKey(), $this->relativeKey($cacheable)];
    }

    /**
     * Get the relative cacheable key.
     *
     * @param Cacheable $cacheable
     * @return string
     */
    protected function relativeKey(Cacheable $cacheable)
    {
        return $cacheable->getCacheKey() . '.' . $cacheable->getKey();
    }

    /**
     * Fire the event.
     *
     * @param mixed $event
     */
    protected function event($event)
    {
        $this->dispatcher->dispatch($event);
    }
}