<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\AliyunServiceProvider;
use Illuminate\Support\Facades\Storage;

class ProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        $app['config']['filesystems.disks.aliyun'] = require __DIR__ . "/../config/config.php";
        return [AliyunServiceProvider::class];
    }

    /**
     * @test
     */
    public function without_default_filesystem_disk()
    {
        $this->expectException(\InvalidArgumentException::class);
        Storage::disk("oss");
    }
}
