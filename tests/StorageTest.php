<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use Illuminate\Contracts\Filesystem\Filesystem;

class StorageTest extends TestCase
{
    public function testInstance()
    {
        $storage = \Illuminate\Support\Facades\Storage::disk('oss');
        $this->assertInstanceOf(Filesystem::class, $storage);

        $adapter = $storage->getAdapter();
        $this->assertInstanceOf(AliyunAdapter::class, $adapter);
    }
}
