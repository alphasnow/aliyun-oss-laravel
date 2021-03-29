<?php

namespace AlphaSnow\AliyunOss\Tests;

class ConfigTest extends TestCase
{
    public function testMultiDiskConfig()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $config['bucket'] = 'multi-bucket';
        $this->app->get('config')->set('filesystems.disks.oss', $config);

        /**
         * @var \Illuminate\Filesystem\FilesystemAdapter $storage
         */
        $storage = $this->app->make('filesystem')->disk('oss');

        $bucket = $storage->getDriver()->getAdapter()->getBucket();
        $this->assertSame('multi-bucket', $bucket);
    }
}
