<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ProviderTest extends TestCase
{
    /**
     * @test
     */
    public function merge_default_config()
    {
        $config = require __DIR__ . "/../config/config.php";
        $appCfg = $this->app["config"]->get("filesystems.disks.oss");
        $this->assertSame($config, $appCfg);
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
