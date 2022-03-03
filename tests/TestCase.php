<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\AliyunServiceProvider;
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
        return [AliyunServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }
}
