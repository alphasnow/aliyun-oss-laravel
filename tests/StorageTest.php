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
    }

    public function testMultiBucket()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config['bucket'] = 'multi-bucket';
        $this->app->get('config')->set('filesystems.disks.oss', $config);

        $storage = $this->app->make('filesystem')->disk('oss');
        $bucket = $storage->getDriver()->getAdapter()->getBucket();

        $this->assertSame($bucket, 'multi-bucket');
    }

    public function testPut()
    {
        $storage = $this->app->make('filesystem')->disk('aliyun');

        $this->ossClient->shouldReceive([
            'putObject' => null
        ]);
        $status = $storage->put('/foo', 'bar');
        $this->assertTrue($status);

        $this->ossClient->shouldReceive([
            'uploadStream' => true
        ]);
        $filePath = __DIR__ . '/stubs/file.txt';

        $resource = fopen($filePath, 'r');
        $status = $storage->put('/foo', $resource);
        fclose($resource);
        $this->assertTrue($status);

        $file = new File($filePath, false);
        $path = $storage->putFile('/foo', $file);
        $this->assertSame(preg_match('/^foo\/.+\.txt$/', $path), 1);

        $path = $storage->putFileAs('/foo', $file, 'bar.txt');
        $this->assertSame($path, 'foo/bar.txt');
    }

    public function testUrl()
    {
        $storage = $this->app->make('filesystem')->disk('aliyun');
        $url = $storage->url('foo/bar.txt');

        $this->assertSame(preg_match('/foo\/bar\.txt$/', $url), 1);
    }
}
