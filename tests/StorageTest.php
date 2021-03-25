<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Filesystem\FilesystemAdapter;
use OSS\OssClient;
use Mockery;

class StorageTest extends TestCase
{
    /**
     * @var OssClient
     */
    protected $ossClient;
    /**
     * @var FilesystemAdapter
     */
    protected $storage;

    public function setUp(): void
    {
        parent::setUp();

        $ossClient = Mockery::mock(OssClient::class,function($mock){
            $mock->makePartial();
        });
        $this->app->instance(OssClient::class,$ossClient);
        $storage = $this->app->make('filesystem')->disk('aliyun');

        $this->ossClient = $ossClient;
        $this->storage = $storage;
    }

    public function testPut()
    {
        $this->ossClient->shouldReceive([
            'putObject' => null
        ]);

        $status = $this->storage->put('/foo.md', 'bar','public');
        $this->assertTrue($status);
    }

    public function testPermission()
    {
        $this->ossClient->shouldReceive([
            'putObject' => null
        ]);

        $status = $this->storage->put('/foo.md', 'bar',[
            OssClient::OSS_STORAGE => OssClient::OSS_STORAGE_IA,
            OssClient::OSS_OBJECT_ACL => OssClient::OSS_ACL_TYPE_PRIVATE,
        ]);
        $this->assertTrue($status);
    }
}
