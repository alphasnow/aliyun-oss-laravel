<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use Illuminate\Contracts\Filesystem\Filesystem;

class StorageTest extends TestCase
{
    public function testInstance()
    {
        $storage = \Illuminate\Support\Facades\Storage::disk('aliyun');
        $this->assertInstanceOf(Filesystem::class, $storage);
    }
}
