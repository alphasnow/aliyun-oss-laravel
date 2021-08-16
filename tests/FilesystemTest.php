<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\Config;
use Illuminate\Filesystem\FilesystemAdapter;
use OSS\OssClient;
use Mockery\MockInterface;

class FilesystemTest extends TestCase
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
            'putObject' => null
        ]);
        $status = $filesystem->put('/foo', 'bar');
        $this->assertTrue($status);
    }

    /**
     * @param FilesystemAdapter $filesystem
     * @param MockInterface $client
     * @dataProvider filesystemProvider
     */
    public function testPutFile($filesystem, $client)
    {
        $client->shouldReceive([
            'uploadStream' => []
        ]);
        $filePath = __DIR__ . '/stubs/file.txt';

        $resource = fopen($filePath, 'r');
        $status = $filesystem->put('/foo', $resource);
        fclose($resource);
        $this->assertTrue($status);

        $file = new \Illuminate\Http\File($filePath, false);
        $path = $filesystem->putFile('/foo', $file);
        $this->assertSame(1, preg_match('/^foo\/.{40}\.txt$/', $path));

        $path = $filesystem->putFileAs('/foo', $file, 'bar.txt');
        $this->assertSame('foo/bar.txt', $path);
    }

    /**
     * @param FilesystemAdapter $filesystem
     * @dataProvider filesystemProvider
     */
    public function testUrl($filesystem)
    {
        $url = $filesystem->url('foo/bar.txt');

        $this->assertSame('http://bucket.endpoint.com/foo/bar.txt', $url);
    }

    /**
     * @param FilesystemAdapter $filesystem
     * @dataProvider filesystemProvider
     */
    public function testTemporaryUrl($filesystem)
    {
        $expiration = new \DateTime('+30 minutes');
        $url = $filesystem->temporaryUrl('foo/bar.txt', $expiration);

        $preg = '/http:\/\/bucket.endpoint.com\/foo\/bar.txt\?OSSAccessKeyId=access_id&Expires=\d{10}&Signature=.+/';
        $this->assertSame(1, preg_match($preg, $url));
    }
}
