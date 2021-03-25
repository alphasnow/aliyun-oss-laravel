<?php

namespace AlphaSnow\AliyunOss\Tests;

use OSS\OssClient;
use Mockery;

class StorageTest extends TestCase
{
    /**
     * @var OssClient
     */
    protected $ossClient;
    protected $storage;

    public function setUp(): void
    {
        parent::setUp();

        $ossClient = Mockery::mock(OssClient::class, function ($mock) {
            $mock->makePartial();
        });
        $this->app->instance(OssClient::class, $ossClient);
        $this->ossClient = $ossClient;

        $storage = $this->app->make('filesystem')->disk('aliyun');
        $this->storage = $storage;
    }

    public function testPut()
    {
        $time = new \DateTime("+8 hour");

        $this->ossClient->shouldReceive([
            'putObject' => null
        ]);

        $status = $this->storage->put('/foo', 'bar');
        $this->assertTrue($status);

        $this->ossClient->shouldReceive([
            'uploadStream' => true
        ]);
        $file = __DIR__ . '/stubs/file.txt';

        $fp = fopen($file, 'r');
        $status = $this->storage->put('/foo', $fp);
        fclose($fp);
        $this->assertTrue($status);

        $path = $this->storage->putFile('/foo', $file);
        $this->assertSame(preg_match('/^foo\/.+\.txt$/', $path), 1);

        $path = $this->storage->putFileAs('/foo', $file, 'bar.txt');
        $this->assertSame($path, 'foo/bar.txt');
    }

    public function testUrl()
    {
        $url = $this->storage->url('foo/bar.txt');

        $this->assertSame(preg_match('/foo\/bar\.txt$/', $url), 1);
    }
}
