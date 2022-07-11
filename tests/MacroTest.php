<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;

class MacroTest extends TestCase
{
    protected $ossClient;

    protected function setUp(): void
    {
        parent::setUp();

        $accessId = getenv("OSS_ACCESS_KEY_ID");
        $accessKey = getenv("OSS_ACCESS_KEY_SECRET");
        $bucket = getenv("OSS_BUCKET");
        $endpoint = getenv("OSS_ENDPOINT");

        $client = \Mockery::mock(OssClient::class, [$accessId,$accessKey,$endpoint])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $this->ossClient = $client;

        $factory = \Mockery::mock(AliyunFactory::class);
        $factory->makePartial()
            ->shouldReceive("createClient")
            ->andReturn($client);
        $this->app->instance(AliyunFactory::class, $factory);
    }

    /**
     * @test
     */
    public function append_file()
    {
        $this->ossClient->shouldReceive("appendFile")
            ->andReturn(7);

        $file = __DIR__."/stubs/file.txt";
        $position = Storage::disk("oss")->appendFile("tests/file.txt", $file);

        $this->assertSame($position, 7);
    }

    /**
     * @test
     */
    public function append_object()
    {
        $this->ossClient->shouldReceive("appendObject")
            ->andReturn(7);

        $position = Storage::disk("oss")->appendObject("tests/file.txt", "content");

        $this->assertSame($position, 7);
    }
}
