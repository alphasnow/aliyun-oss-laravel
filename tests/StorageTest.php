<?php

namespace AlphaSnow\AliyunOss\Tests;

class StorageTest extends TestCase
{
    public function testInstance()
    {
        $storage = \Illuminate\Support\Facades\Storage::disk('aliyun');
        $this->assertInstanceOf(\Illuminate\Contracts\Filesystem\Filesystem::class, $storage);

        $filesystem = $storage->getDriver();
        $this->assertInstanceOf(\League\Flysystem\FilesystemInterface::class, $filesystem);

        $adapter = $filesystem->getAdapter();
        $this->assertInstanceOf(\League\Flysystem\AdapterInterface::class, $adapter);

        $callAdapter = $storage->getAdapter();
        $this->assertSame($adapter, $callAdapter);

        $client = $adapter->getClient();
        $this->assertInstanceOf(\OSS\OssClient::class, $client);
    }
}
