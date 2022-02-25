<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\ServiceProvider;
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
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }
}
