<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssAdapter;
use League\Flysystem\Config;
use OSS\OssClient;

class AdapterTest extends TestCase
{
    public function adapterProvider()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $ossConfig = $this->toOssClientParameters($config);
        $client = \Mockery::mock(OssClient::class, array_values($ossConfig))
            ->makePartial();
        $adapter = new AliyunOssAdapter($client, $config);
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

        $this->assertSame('http://bucket.oss-cn-shanghai.aliyuncs.com/foo/bar.txt', $url);
    }

    public function testCdnUrl()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config['is_ssl'] = true;
        $config['is_cname'] = true;
        $config['cdn_domain'] = 'www.cdn-domain.com';

        $adapter = new AliyunOssAdapter($this->app->make(OssClient::class, $this->toOssClientParameters($config)), $config);
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

        $preg = '/http:\/\/bucket.oss-cn-shanghai.aliyuncs.com\/foo\/bar.txt\?OSSAccessKeyId=access_id&Expires=\d{10}&Signature=.+/';
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
