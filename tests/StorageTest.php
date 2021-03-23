<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class StorageTest extends TestCase
{
    public function testDist()
    {
        $disk = Storage::disk('aliyun');

        $this->assertTrue($disk instanceof FilesystemAdapter);
    }
}
