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
        $defaultConfig = require __DIR__.'/../src/config/config.php';
        $config = new AliyunOssConfig($defaultConfig);
        $clientParameters = $config->getOssClientParameters();
        $client = \Mockery::mock(OssClient::class, array_values($clientParameters))
            ->makePartial();
        $adapter = new AliyunOssAdapter($client, $config);
        return [
            [$adapter,$config]
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

    /**
     * @dataProvider adapterProvider
     */
    public function testDomainUrl($adapter, $config)
    {
        $config['domain'] = 'www.domain.com';
        $url = $adapter->getUrl('foo/bar.txt');
        $this->assertSame('http://www.domain.com/foo/bar.txt', $url);
    }

    /**
     * @dataProvider adapterProvider
     */
    public function testTemporaryUrl($adapter)
    {
        $url = $adapter->getTemporaryUrl('foo/bar.txt', new \DateTime('+30 minutes'));
        $preg = '/http:\/\/bucket.endpoint.com\/foo\/bar.txt\?OSSAccessKeyId=access_id&Expires=\d{10}&Signature=.+/';
        $this->assertSame(1, preg_match($preg, $url));
    }

    public function testGetOptionsFromConfig()
    {
        $adapter = \Mockery::mock(AliyunOssAdapter::class);
        $adapter->makePartial()->shouldAllowMockingProtectedMethods();
        $options = $adapter->getOptionsFromConfig(new Config(['visibility' => 'private']));
        $this->assertSame([OssClient::OSS_HEADERS => [OssClient::OSS_OBJECT_ACL => 'private']], $options);
    }
}
