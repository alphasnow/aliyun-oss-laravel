<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use AlphaSnow\LaravelFilesystem\Aliyun\AliyunServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ProviderTest extends TestCase
{
    /**
     * @test
     */
    public function aliyun_factory_singleton()
    {
        $factory1 = $this->app->make(AliyunFactory::class);
        $factory2 = $this->app->make(AliyunFactory::class);

        $this->assertSame($factory1, $factory2);
    }

    /**
     * @test
     */
    public function merge_default_config()
    {
        $config = require __DIR__ . "/../config/config.php";
        $appCfg = $this->app["config"]->get("filesystems.disks.oss");
        $this->assertSame($config,$appCfg);
    }

    /**
     * @test
     */
    public function default_filesystem_disk()
    {
        $storage = Storage::disk("oss");
        $this->assertInstanceOf(Filesystem::class, $storage);
    }

}
