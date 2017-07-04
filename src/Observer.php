<?php

namespace Michaeljennings\Broker;

use Illuminate\Database\Eloquent\Model;
use Michaeljennings\Broker\Contracts\Broker;

class Observer
{
    /**
     * The broker implementation.
     *
     * @var Broker
     */
    protected $broker;

    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }

    /**
     * @param Model $model
     */
    public function saved(Model $model)
    {
        $this->flush($model);
    }

    /**
     * @param Model $model
     */
    public function updated(Model $model)
    {
        $this->flush($model);
    }

    /**
     * @param Model $model
     */
    public function restored(Model $model)
    {
        $this->flush($model);
    }

    /**
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        $this->flush($model);
    }

    /**
     * Flush the broker cache for the model.
     *
     * @param Model $model
     */
    protected function flush(Model $model)
    {
        $this->broker->flush($model);
    }
}