<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * https://github.com/orchestral/testbench
 *
 * Class TestCase
 * @package AlphaSnow\AliyunOss\Tests
 */
class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [AliyunOssServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

}