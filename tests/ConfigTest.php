<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\Config;

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
        $ossConfig = new Config($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['endpoint'], $endpoint);

        $config['use_domain_endpoint'] = true;
        $ossConfig = new Config($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['domain'], $endpoint);

        $config['internal'] = 'oss-cn-shanghai-internal.aliyuncs.com';
        $ossConfig = new Config($config);
        $endpoint = $ossConfig->getOssEndpoint();
        $this->assertSame($config['internal'], $endpoint);
    }

    public function testCorrectUrl()
    {
        $config = [
            'use_ssl' => false,
            'bucket' => 'bucket',
            'endpoint' => 'oss-cn-shanghai.aliyuncs.com',
            'internal' => 'oss-cn-shanghai-internal.aliyuncs.com',
        ];
        $ossConfig = new Config($config);
        $internalUrl = 'http://bucket.oss-cn-shanghai-internal.aliyuncs.com/dir/path/file.txt';
        $correctUrl = $ossConfig->correctUrl($internalUrl);
        $this->assertSame('http://bucket.oss-cn-shanghai.aliyuncs.com/dir/path/file.txt', $correctUrl);

        $config = [
            'use_ssl' => false,
            'bucket' => 'bucket',
            'endpoint' => 'oss-cn-shanghai.aliyuncs.com',
            'domain' => 'oss.my-domain.com',
            'use_domain_endpoint' => false,
        ];
        $ossConfig = new Config($config);
        $endpointUrl = 'http://bucket.oss-cn-shanghai.aliyuncs.com/dir/path/file.txt';
        $correctUrl = $ossConfig->correctUrl($endpointUrl);
        $this->assertSame('http://oss.my-domain.com/dir/path/file.txt', $correctUrl);
    }
}
