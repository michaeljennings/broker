<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Contracts\Broker;

class ServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_registers_the_service()
    {
        $this->assertInstanceOf(Broker::class, $this->app->make(Broker::class));
        $this->assertInstanceOf(Broker::class, $this->app->make('broker'));
    }
}