<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use Illuminate\Contracts\Filesystem\Filesystem;
use OSS\OssClient;
use Illuminate\Support\Facades\Storage;

class StorageTest extends TestCase
{
    /**
     * @test
     */
    public function get_oss_storage()
    {
        $storage = Storage::disk("oss");
        $this->assertInstanceOf(Filesystem::class, $storage);
    }

    /**
     * @test
     */
    public function get_oss_client()
    {
        $adapter = Storage::disk("oss")->getAdapter();
        $this->assertInstanceOf(AliyunAdapter::class, $adapter);

        $client = $adapter->getClient();
        $this->assertInstanceOf(OssClient::class, $client);
    }
}
