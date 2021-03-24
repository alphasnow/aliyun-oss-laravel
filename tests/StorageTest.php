<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\AbstractAdapter;
use OSS\OssClient;

class StorageTest extends TestCase
{
    public function testDisk()
    {
        $storage = Storage::disk('aliyun');
        $this->assertTrue($storage instanceof FilesystemAdapter);

        $adapter = $storage->getDriver()->getAdapter();
        $this->assertTrue($adapter instanceof AbstractAdapter);

        $client = \Mockery::mock(OssClient::class);
        $client->shouldReceive(['doesObjectExist' => false,'putObject' => null]);
        $adapter->setClient($client);

        $putStatus = $storage->put('/tests.log', 'tests');
        $this->assertTrue($putStatus);
    }
}
