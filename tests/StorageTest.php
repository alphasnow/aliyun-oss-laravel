<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Http\File;
use OSS\OssClient;
use Mockery;

class StorageTest extends TestCase
{
    /**
     * @var OssClient
     */
    protected $ossClient;
    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
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
        $this->ossClient->shouldReceive([
            'putObject' => null
        ]);

        $status = $this->storage->put('/foo', 'bar');
        $this->assertTrue($status);

        $this->ossClient->shouldReceive([
            'uploadStream' => true
        ]);
        $filePath = __DIR__ . '/stubs/file.txt';

        $resource = fopen($filePath, 'r');
        $status = $this->storage->put('/foo', $resource);
        fclose($resource);
        $this->assertTrue($status);

        $file = new File($filePath,false);
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
