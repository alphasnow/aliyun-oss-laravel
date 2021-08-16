<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\Config;
use Illuminate\Filesystem\FilesystemAdapter;
use OSS\OssClient;
use Mockery\MockInterface;

class FilesystemPluginTest extends TestCase
{
    public function filesystemProvider()
    {
        $this->setUpTheTestEnvironment();

        $config = require __DIR__.'/../src/config/config.php';
        $ossClientParameters = (new Config($config))->getOssClientParameters();
        $client = \Mockery::mock(OssClient::class, array_values($ossClientParameters))
            ->makePartial();
        $this->app->singleton('aliyun-oss.oss-client', function ($app) use ($client) {
            return $client;
        });
        $filesystem = $this->app->make('filesystem')->disk('aliyun');
        return [
          [$filesystem,$client]
        ];
    }

    /**
     * @param FilesystemAdapter $filesystem
     * @param MockInterface $client
     * @dataProvider filesystemProvider
     */
    public function testPut($filesystem, $client)
    {
        $client->shouldReceive([
            'uploadStream' => null
        ]);

        $filePath = __DIR__ . '/stubs/file.txt';
        $status = $filesystem->putRemoteFile('/foo/bar.txt', 'file://'.$filePath);
        $this->assertTrue($status);
    }
}
