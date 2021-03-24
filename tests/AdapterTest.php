<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssAdapter;
use AlphaSnow\AliyunOss\AliyunOssConfig;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use OSS\OssClient;
use Mockery;

class AdapterTest extends TestCase
{
    protected $aliyunConfig = [
        'accessId' => 'access_id',
        'accessKey' => 'access_key',
        'bucket' => 'bucket',
        'endpoint' => 'endpoint'
    ];
    public function aliyunProvider()
    {
        $config = Mockery::mock(Config::class, ['disable_asserts' => true])->makePartial();
        $ossClient = Mockery::mock(OssClient::class, [$this->aliyunConfig['accessId'],$this->aliyunConfig['accessKey'],$this->aliyunConfig['bucket'],$this->aliyunConfig['endpoint']])->makePartial();
        $ossConfig = Mockery::mock(AliyunOssConfig::class, [$this->aliyunConfig])->makePartial();
        $adapter = Mockery::mock(AliyunOssAdapter::class, [$ossClient, $ossConfig])->makePartial()->shouldAllowMockingProtectedMethods();

        return [
            [$adapter,$ossClient,$config]
        ];
    }

    /**
     * @dataProvider aliyunProvider
     */
    public function testInstance($adapter)
    {
        $this->assertTrue($adapter instanceof AbstractAdapter);
    }

    /**
     * @dataProvider aliyunProvider
     */
    public function testWrite($adapter, $ossClient, $config)
    {
        $ossClient->shouldReceive(['putObject' => null]);
        $result = $adapter->write('dir/file.txt', 'contents', $config);

        $this->assertWriteResult($result);
    }

    /**
     * @dataProvider aliyunProvider
     */
    public function testWriteStream($adapter, $ossClient, $config)
    {
        $ossClient->shouldReceive(['putObject' => null]);

        $fp = fopen(__DIR__.'/stubs/file.txt', 'r');
        $result = $adapter->writeStream('dir/file.txt', $fp, $config);

        $this->assertWriteResult($result);
    }

    protected function assertWriteResult($result)
    {
        $this->assertIsArray($result);
        $this->assertSame($result['path'], 'dir/file.txt');
        $this->assertSame($result['dirname'], 'dir');
        $this->assertSame($result['type'], 'file');
    }

    /**
     * @dataProvider aliyunProvider
     */
    public function testWriteFile($adapter, $ossClient, $config)
    {
        $ossClient->shouldReceive(['uploadFile' => null]);

        $filePath = __DIR__.'/stubs/file.txt';
        $result = $adapter->writeFile('dir/file.txt', $filePath, $config);

        $this->assertWriteResult($result);
    }
}
