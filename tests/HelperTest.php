<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Contracts\Broker;
use Michaeljennings\Broker\Tests\Fixtures\TestModel;

class HelperTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_the_broker_instance()
    {
        $broker = broker();

        $this->assertInstanceOf(Broker::class, $broker);
    }

    /**
     * @test
     */
    public function it_gets_an_item_from_the_cache()
    {
        $cacheable = new TestModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $this->assertEquals('bar', broker($cacheable, 'foo'));
    }
}