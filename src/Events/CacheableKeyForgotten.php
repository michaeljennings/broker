<?php

namespace Michaeljennings\Broker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Michaeljennings\Broker\Contracts\Cacheable;

class CacheableKeyForgotten
{
    use Dispatchable, SerializesModels;

    /**
     * The cacheable entity.
     *
     * @var Cacheable
     */
    public $cacheable;

    /**
     * The keys that was forgotten.
     *
     * @var string
     */
    public $keys;

    /**
     * Create a new event instance.
     *
     * @param Cacheable $cacheable
     * @param array     $keys
     */
    public function __construct(Cacheable $cacheable, $keys)
    {
        $this->cacheable = $cacheable;
        $this->keys = $keys;
    }
}
