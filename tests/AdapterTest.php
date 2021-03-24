<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssConfig;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class AdapterTest extends TestCase
{
    public function testInstance()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config = new AliyunOssConfig($config);

        $disk = Storage::disk('aliyun');
        $this->assertTrue($disk instanceof FilesystemAdapter);
    }
}
