<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssAdapter;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use OSS\OssClient;
use Mockery;

class AdapterTest extends TestCase
{
    /**
     * @var Config
     */
    protected $systemConfig;
    /**
     * @var OssClient
     */
    protected $mockClient;
    /**
     * @var AliyunOssAdapter
     */
    protected $adapter;

    public function setUp(): void
    {
        parent::setUp();

        $this->systemConfig = new Config(['disable_asserts' => true]);
        $this->mockClient = Mockery::mock(OssClient::class)->makePartial();
        $this->adapter = $this->app->make(AliyunOssAdapter::class, ['client' => $this->mockClient]);
    }

    public function testInstance()
    {
        $this->assertTrue($this->adapter instanceof AbstractAdapter);
    }

    public function testWrite()
    {
        $this->mockClient->shouldReceive(['putObject' => null]);
        $result = $this->adapter->write('dir/file.txt', 'contents', $this->systemConfig);

        $this->assertWriteResult($result);
    }

    public function testWriteStream()
    {
        $this->mockClient->shouldReceive(['putObject' => null]);
        $fp = fopen(__DIR__.'/stubs/file.txt', 'r');

        $result = $this->adapter->writeStream('dir/file.txt', $fp, $this->systemConfig);

        $this->assertWriteResult($result);
    }

    protected function assertWriteResult($result)
    {
        $this->assertIsArray($result);
        $this->assertSame($result['path'], 'dir/file.txt');
        $this->assertSame($result['dirname'], 'dir');
        $this->assertSame($result['type'], 'file');
    }

    public function testWriteFile()
    {
        $this->mockClient->shouldReceive(['uploadFile' => null]);
        $filePath = __DIR__.'/stubs/file.txt';

        $result = $this->adapter->writeFile('dir/file.txt', $filePath, $this->systemConfig);

        $this->assertWriteResult($result);
    }
}
