<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\Tests\Fixtures\TestModel;

class BrokerTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache()
    {
        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->put($cacheable, 'foo', 'bar', 60);

        $this->assertEquals($this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->id])->get('foo'), 'bar');
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_forever()
    {
        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->forever($cacheable, 'foo', 'bar');

        $this->assertEquals($this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->id])->get('foo'), 'bar');
    }

    /**
     * @test
     */
    public function it_stores_an_item_in_the_cache_using_a_callback()
    {
        $cacheable = new TestModel(['id' => 1]);

        $this->makeBroker()->remember($cacheable, 'foo', function() {
            return 'bar';
        });

        $this->assertEquals($this->app->make('cache')->tags([$cacheable->getCacheKey(), $cacheable->id])->get('foo'), 'bar');
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
        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');

        $broker->forget($cacheable, 'foo');

        $this->assertNull($broker->get($cacheable, 'foo'));
    }

    /**
     * @test
     */
    public function it_removes_multiple_items_from_the_cache()
    {
        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');
        $broker->put($cacheable, 'baz', 'qux');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');
        $this->assertEquals($broker->get($cacheable, 'baz'), 'qux');

        $broker->forget($cacheable, ['foo', 'baz']);

        $this->assertNull($broker->get($cacheable, 'foo'));
        $this->assertNull($broker->get($cacheable, 'baz'));
    }

    /**
     * @test
     */
    public function it_fluses_all_of_the_keys_for_a_cacheable_entity()
    {
        $broker = $this->makeBroker();
        $cacheable = new TestModel(['id' => 1]);

        $broker->put($cacheable, 'foo', 'bar');
        $broker->put($cacheable, 'baz', 'qux');

        $this->assertEquals($broker->get($cacheable, 'foo'), 'bar');
        $this->assertEquals($broker->get($cacheable, 'baz'), 'qux');

        $broker->flush($cacheable);

        $this->assertNull($broker->get($cacheable, 'foo'));
        $this->assertNull($broker->get($cacheable, 'baz'));
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