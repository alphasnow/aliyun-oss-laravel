<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\AliyunServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ProviderWithExistConfigTest extends TestCase
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
        $storage = Storage::disk("aliyun");
        $this->assertInstanceOf(Filesystem::class, $storage);

        $this->expectException(\InvalidArgumentException::class);
        Storage::disk("oss");
    }
}
