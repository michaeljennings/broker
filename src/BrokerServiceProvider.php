<?php

namespace Michaeljennings\Broker;

use Illuminate\Support\ServiceProvider;

class BrokerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Michaeljennings\Broker\Contracts\Broker', 'Michaeljennings\Broker\Broker');
        $this->app->alias('Michaeljennings\Broker\Contracts\Broker', 'broker');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Michaeljennings\Broker\Contracts\Broker',
            'broker',
        ];
    }
}
