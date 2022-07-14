<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }
}
