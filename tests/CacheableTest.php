<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Tests\Fixtures\CacheableModel;

class CacheableTest extends TestCase
{
    /**
     * @test
     */
    public function by_default_it_gets_the_table_as_the_cache_key()
    {
        $cacheable = new CacheableModel();

        $this->assertEquals('test_data', $cacheable->getCacheKey());
    }

    /**
     * @test
     */
    public function it_clears_the_cache_for_the_cacheable_entity()
    {
        $cacheable = new CacheableModel(['id' => 1]);

        broker()->put($cacheable, 'foo', 'bar');

        $cacheable->flushCache();

        $this->assertNull(broker()->get($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_clears_the_cache_for_the_cacheable_type()
    {
        $cacheable = new CacheableModel(['id' => 1]);
        $cacheable2 = new CacheableModel(['id' => 2]);

        broker()->put($cacheable, 'foo', 'bar');
        broker()->put($cacheable2, 'baz', 'qux');

        $cacheable->flushAll();

        $this->assertNull(broker()->get($cacheable, 'foo'));
        $this->assertNull(broker()->get($cacheable, 'baz'));
    }
}