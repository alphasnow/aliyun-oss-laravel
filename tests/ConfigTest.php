<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssConfig;

class ConfigTest extends TestCase
{
    public function testMultiDiskConfig()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config['bucket'] = 'multi-bucket';
        $this->app->get('config')->set('filesystems.disks.oss', $config);

        /**
         * @var \Illuminate\Filesystem\FilesystemAdapter $storage
         */
        $storage = $this->app->make('filesystem')->disk('oss');

        $bucket = $storage->getDriver()->getAdapter()->getBucket();
        $this->assertSame('multi-bucket', $bucket);
    }

    public function testGetOssEndpoint()
    {
        $config = [
            'endpoint' => 'oss-cn-shanghai.aliyuncs.com',
            'interial' => null,
            'domain' => 'oss.my-domain.com',
            'use_domain_endpoint' => false,
        ];
        $ossConfig = new AliyunOssConfig($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['endpoint'], $endpoint);

        $config['internal'] = 'oss-cn-shanghai-internal.aliyuncs.com';
        $ossConfig = new AliyunOssConfig($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['internal'], $endpoint);

        $config['use_domain_endpoint'] = true;
        $ossConfig = new AliyunOssConfig($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['domain'], $endpoint);
    }
}
