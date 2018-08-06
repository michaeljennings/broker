<?php

namespace Michaeljennings\Broker\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Michaeljennings\Broker\Contracts\Cacheable;

class CacheableFlushed
{
    use Dispatchable, SerializesModels;

    /**
     * The cacheable entity that was flushed.
     *
     * @var Cacheable
     */
    public $cacheable;

    /**
     * Create a new event instance.
     *
     * @param Cacheable $cacheable
     */
    public function __construct(Cacheable $cacheable)
    {
        $this->cacheable = $cacheable;
    }
}
