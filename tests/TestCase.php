<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssClient;
use AlphaSnow\AliyunOss\ServiceProvider;
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
        return ['AliyunOssClient' => AliyunOssClient::class];
    }

    protected function toOssClientParameters(array $config)
    {
        return [
            'accessKeyId' => $config['accessId'],
            'accessKeySecret' => $config['accessKey'],
            'endpoint' => $config['endpoint'],
            'isCName' => $config['isCname'],
            'securityToken' => $config['securityToken']
        ];
    }
}
