<?php

namespace Michaeljennings\Broker\Facades;

use Illuminate\Support\Facades\Facade;

class Broker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'broker';
    }
}