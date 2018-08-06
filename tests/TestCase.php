<?php

namespace Michaeljennings\Broker\Tests;

use Michaeljennings\Broker\BrokerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @inheritdoc
     */
    public function getPackageProviders($app)
    {
        return [
            BrokerServiceProvider::class,
        ];
    }
}