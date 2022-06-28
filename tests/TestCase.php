<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\AliyunServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @link https://github.com/orchestral/testbench
 */
class TestCase extends BaseTestCase
{
    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function getPackageProviders($app)
    {
        return [AliyunServiceProvider::class];
    }
}
