<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;

class StorageTest extends TestCase
{
    public function testDisk()
    {
        $storage = Storage::disk('aliyun');
        $this->assertTrue($storage instanceof FilesystemAdapter);

        $adapter = $storage->getDriver()->getAdapter();

        $client = \Mockery::mock(OssClient::class);
        $client->shouldReceive(['doesObjectExist'=>false,'putObject'=>null]);
        $adapter->setClient($client);

        $result = $storage->put('/tests.log','tests');
    }
}
