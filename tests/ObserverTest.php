<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Observer;
use Michaeljennings\Broker\Tests\Fixtures\TestModel;

class ObserverTest extends TestCase
{
    /**
     * @test
     */
    public function it_flushes_the_cache_when_an_entity_is_saved()
    {
        $observer = $this->makeObserver();
        $cacheable = new TestModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $observer->saved($cacheable);

        $this->assertNull(broker()->get($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_flushes_the_cache_when_an_entity_is_updated()
    {
        $observer = $this->makeObserver();
        $cacheable = new TestModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $observer->updated($cacheable);

        $this->assertNull(broker()->get($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_flushes_the_cache_when_an_entity_is_restored()
    {
        $observer = $this->makeObserver();
        $cacheable = new TestModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $observer->restored($cacheable);

        $this->assertNull(broker()->get($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_flushes_the_cache_when_an_entity_is_deleted()
    {
        $observer = $this->makeObserver();
        $cacheable = new TestModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $observer->deleted($cacheable);

        $this->assertNull(broker()->get($cacheable, 'foo'));
    }

    protected function makeObserver()
    {
        return new Observer();
    }
}