<?php

namespace Michaeljennings\Broker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Michaeljennings\Broker\Contracts\Cacheable;

class CacheableKeyWritten
{
    use Dispatchable, SerializesModels;

    /**
     * The cacheable entity.
     *
     * @var Cacheable
     */
    public $cacheable;

    /**
     * The key that was written to the cache.
     *
     * @var string
     */
    public $key;

    /**
     * How long the key will be stored for. If 0 it will be forever.
     *
     * @var int
     */
    public $minutes;

    /**
     * Create a new event instance.
     *
     * @param Cacheable $cacheable
     * @param string    $key
     * @param int       $minutes
     */
    public function __construct(Cacheable $cacheable, $key, $minutes)
    {
        $this->cacheable = $cacheable;
        $this->key = $key;
        $this->minutes = $minutes;
    }
}
