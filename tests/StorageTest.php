<?php

namespace AlphaSnow\AliyunOss\Tests;

use OSS\OssClient;

class StorageTest extends TestCase
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

        $this->assertSame($bucket, 'multi-bucket');
    }

    public function testPut()
    {
        $ossClient = $this->partialMock(OssClient::class);
        $this->app->singleton(OssClient::class, function ($app) use ($ossClient) {
            return $ossClient;
        });

        /**
         * @var \Illuminate\Filesystem\FilesystemAdapter $storage
         */
        $storage = $this->app->make('filesystem')->disk('aliyun');

        $ossClient->shouldReceive([
            'putObject' => null
        ]);
        $status = $storage->put('/foo', 'bar');
        $this->assertTrue($status);

        $ossClient->shouldReceive([
            'uploadStream' => true
        ]);
        $filePath = __DIR__ . '/stubs/file.txt';

        $resource = fopen($filePath, 'r');
        $status = $storage->put('/foo', $resource);
        fclose($resource);
        $this->assertTrue($status);

        $file = new \Illuminate\Http\File($filePath, false);
        $path = $storage->putFile('/foo', $file);
        $this->assertSame(preg_match('/^foo\/.+\.txt$/', $path), 1);

        $path = $storage->putFileAs('/foo', $file, 'bar.txt');
        $this->assertSame($path, 'foo/bar.txt');
    }

    public function testUrl()
    {
        /**
         * @var \Illuminate\Filesystem\FilesystemAdapter $storage
         */
        $storage = $this->app->make('filesystem')->disk('aliyun');

        $url = $storage->url('foo/bar.txt');

        $this->assertSame(preg_match('/foo\/bar\.txt$/', $url), 1);
    }
}
