<?php

namespace Michaeljennings\Broker\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Michaeljennings\Broker\Contracts\Cacheable as CacheableContract;
use Michaeljennings\Broker\Traits\Cacheable;

class CacheableModel extends Model implements CacheableContract
{
    use Cacheable;

    protected $table = 'test_data';
    protected $guarded = [];
}