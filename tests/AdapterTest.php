<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssAdapter;
use AlphaSnow\AliyunOss\AliyunOssConfig;
use League\Flysystem\Config;
use OSS\OssClient;

class AdapterTest extends TestCase
{
    public function adapterProvider()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $ossConfig = new AliyunOssConfig($config);
        $ossClientParameters = $ossConfig->getOssClientParameters();
        $client = \Mockery::mock(OssClient::class, array_values($ossClientParameters))
            ->makePartial();
        $adapter = new AliyunOssAdapter($client, $ossConfig);
        return [
            [$adapter,$client]
        ];
    }

    /**
     * @dataProvider adapterProvider
     */
    public function testUrl($adapter)
    {
        $url = $adapter->getUrl('foo/bar.txt');

        $this->assertSame('http://bucket.endpoint.com/foo/bar.txt', $url);
    }

    public function testCdnUrl()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config['use_ssl'] = true;
        $config['domain'] = 'www.cdn-domain.com';
        $ossConfig = new AliyunOssConfig($config);
        $ossClientParameters = $ossConfig->getOssClientParameters();
        $ossClient = $this->app->make(OssClient::class, $ossClientParameters);
        $adapter = new AliyunOssAdapter($ossClient, $ossConfig);

        $url = $adapter->getUrl('foo/bar.txt');

        $this->assertSame('https://www.cdn-domain.com/foo/bar.txt', $url);
    }

    /**
     * @param AliyunOssAdapter $adapter
     * @dataProvider adapterProvider
     */
    public function testTemporaryUrl($adapter)
    {
        $expiration = new \DateTime('+30 minutes');
        $url = $adapter->getTemporaryUrl('foo/bar.txt', $expiration);

        $preg = '/http:\/\/bucket.endpoint.com\/foo\/bar.txt\?OSSAccessKeyId=access_id&Expires=\d{10}&Signature=.+/';
        $this->assertSame(1, preg_match($preg, $url));
    }

    public function testGetOptionsFromConfig()
    {
        $adapter = \Mockery::mock(AliyunOssAdapter::class);
        $adapter->makePartial()->shouldAllowMockingProtectedMethods();

        $options = $adapter->getOptionsFromConfig(new Config(['visibility' => 'private']));
        $this->assertSame([OssClient::OSS_OBJECT_ACL => 'private'], $options);
    }
}
