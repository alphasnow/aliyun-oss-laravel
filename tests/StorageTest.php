<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use Illuminate\Contracts\Filesystem\Filesystem;
use OSS\OssClient;
use Illuminate\Support\Facades\Storage;

class StorageTest extends TestCase
{
    public function testInstance()
    {
        $storage = Storage::disk("oss");
        $this->assertInstanceOf(Filesystem::class, $storage);
    }

    public function testClient()
    {
        $adapter = Storage::disk("oss")->getAdapter();
        $this->assertInstanceOf(AliyunAdapter::class, $adapter);

        $client = $adapter->getClient();
        $this->assertInstanceOf(OssClient::class, $client);

        $bucket = $adapter->getBucket();
        $this->assertSame(config("filesystems.disks.oss.bucket"), $bucket);
    }
}
