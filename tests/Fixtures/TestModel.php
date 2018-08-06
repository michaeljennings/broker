<?php

namespace Michaeljennings\Broker\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Michaeljennings\Broker\Contracts\Cacheable as CacheableContract;

class TestModel extends Model implements CacheableContract
{
    protected $table = 'test_data';
    protected $guarded = [];

    /**
     * Get the key to cache the attributes against.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->type ?: $this->table;
    }
}