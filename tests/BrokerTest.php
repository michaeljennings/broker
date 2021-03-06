<?php

namespace Michaeljennings\Broker\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Michaeljennings\Broker\Events\CacheableFlushed;
use Michaeljennings\Broker\Events\CacheableKeyForgotten;
use Michaeljennings\Broker\Events\CacheableKeyWritten;
use Michaeljennings\Broker\Tests\Fixtures\TestModel;

class BrokerTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache()
    {
        Event::fake();

        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->put($cacheable, 'foo', 'bar', 60);

        $this->assertEquals(
            $this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->getCacheKey().'.'.$cacheable->id])->get('foo'),
            'bar'
        );

        Event::assertDispatched(CacheableKeyWritten::class);
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_and_passes_the_ttl_as_carbon()
    {
        Event::fake();

        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->put($cacheable, 'foo', 'bar', (new Carbon())->addMinutes(60));

        $this->assertEquals(
            $this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->getCacheKey().'.'.$cacheable->id])->get('foo'),
            'bar'
        );

        Event::assertDispatched(CacheableKeyWritten::class);
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_forever()
    {
        Event::fake();

        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->forever($cacheable, 'foo', 'bar');

        $this->assertEquals(
            $this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->getCacheKey().'.'.$cacheable->id])->get('foo'),
            'bar'
        );

        Event::assertDispatched(CacheableKeyWritten::class);
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_using_a_callback()
    {
        Event::fake();

        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->remember($cacheable, 'foo', function() {
            return 'bar';
        });

        $this->assertEquals(
            $this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->getCacheKey().'.'.$cacheable->id])->get('foo'),
            'bar'
        );

        Event::assertDispatched(CacheableKeyWritten::class);
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_using_a_callback_and_sets_the_ttl_as_carbon()
    {
        Event::fake();

        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->remember($cacheable, 'foo', function() {
            return 'bar';
        }, (new Carbon())->addMinutes(60));

        $this->assertEquals(
            $this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->getCacheKey().'.'.$cacheable->id])->get('foo'),
            'bar'
        );

        Event::assertDispatched(CacheableKeyWritten::class);
    }

    /**
     * @test
     */
    public function it_retrieves_an_item_from_the_cache()
    {
        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');
    }

    /**
     * @test
     */
    public function it_checks_if_an_item_is_in_the_cache()
    {
        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $this->assertFalse($broker->has($cacheable, 'foo'));

        $broker->put($cacheable, 'foo', 'bar');

        $this->assertTrue($broker->has($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_removes_an_item_from_the_cache()
    {
        Event::fake();

        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');

        $broker->forget($cacheable, 'foo');

        $this->assertNull($broker->get($cacheable, 'foo'));

        Event::assertDispatched(CacheableKeyForgotten::class);
    }

    /**
     * @test
     */
    public function it_removes_multiple_items_from_the_cache()
    {
        Event::fake();

        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');
        $broker->put($cacheable, 'baz', 'qux');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');
        $this->assertEquals($broker->get($cacheable, 'baz'), 'qux');

        $broker->forget($cacheable, ['foo', 'baz']);

        $this->assertNull($broker->get($cacheable, 'foo'));
        $this->assertNull($broker->get($cacheable, 'baz'));

        Event::assertDispatched(CacheableKeyForgotten::class);
    }

    /**
     * @test
     */
    public function it_flushes_all_of_the_keys_for_a_cacheable_entity()
    {
        Event::fake();

        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);
        $cacheable2 = new TestModel(['id' => 2]);

        $broker->put($cacheable, 'foo', 'bar');
        $broker->put($cacheable, 'baz', 'qux');
        $broker->put($cacheable2, 'foo', 'qux');

        $broker->flush($cacheable);

        $this->assertNull($broker->get($cacheable, 'foo'));
        $this->assertNull($broker->get($cacheable, 'baz'));
        $this->assertEquals('qux', $broker->get($cacheable2, 'foo'));

        Event::assertDispatched(CacheableFlushed::class);
    }

    /**
     * @test
     */
    public function it_removes_all_of_the_cache_for_a_cacheable_type()
    {
        $broker = $this->makeBroker();

        $user1 = new TestModel(['id' => 1]);
        $user2 = new TestModel(['id' => 2]);
        $admin = new TestModel(['id' => 1, 'type' => 'admin']);

        $broker->put($user1, 'foo', 'bar');
        $broker->put($user2, 'foo', 'bar');
        $broker->put($admin, 'foo', 'bar');

        $broker->flushAll(TestModel::class);

        $this->assertNull($broker->get($user1, 'foo'));
        $this->assertNull($broker->get($user2, 'foo'));
        $this->assertEquals($broker->get($admin, 'foo'), 'bar');
    }

    /**
     * @test
     */
    public function it_removes_all_of_the_cache_all_against_a_tag()
    {
        $broker = $this->makeBroker();

        $user1 = new TestModel(['id' => 1, 'type' => 'user']);
        $user2 = new TestModel(['id' => 2, 'type' => 'user']);
        $admin = new TestModel(['id' => 1, 'type' => 'admin']);

        $broker->put($user1, 'foo', 'bar');
        $broker->put($user2, 'foo', 'bar');
        $broker->put($admin, 'foo', 'bar');

        $broker->flushTags('user');

        $this->assertNull($broker->get($user1, 'foo'));
        $this->assertNull($broker->get($user2, 'foo'));
        $this->assertEquals($broker->get($admin, 'foo'), 'bar');
    }

    protected function makeBroker()
    {
        return $this->app->make('broker');
    }
}
