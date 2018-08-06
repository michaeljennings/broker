<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Facades\Broker;
use Michaeljennings\Broker\Tests\Fixtures\TestModel;

class FacadeTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_accessed_from_its_facade()
    {
        $cacheable = new TestModel(['id' => 1]);

        Broker::put($cacheable, 'foo', 'bar');

        $this->assertEquals('bar', Broker::get($cacheable, 'foo'));
    }
}