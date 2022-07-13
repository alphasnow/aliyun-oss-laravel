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

        $client = \Mockery::mock(OssClient::class, ["access_id","access_secret","endpoint.com"]);
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
        $file = __DIR__."/stubs/file.txt";

        $this->ossClient->shouldReceive("appendFile")
            ->with("bucket", "tests/stubs/file.txt", $file, 0, ["headers" => ["Content-Disposition" => "attachment;filename=file.txt"]])
            ->once()
            ->andReturn(7);
        $position = Storage::disk("oss")->appendFile("stubs/file.txt", $file, 0, ["headers" => ["Content-Disposition" => "attachment;filename=file.txt"]]);
        $this->assertSame($position, 7);
    }

    /**
     * @test
     */
    public function append_object()
    {
        $this->ossClient->shouldReceive("appendObject")
            ->with("bucket", "tests/stubs/file.txt", "contents", 0, ["checkmd5" => false])
            ->once()
            ->andReturn(8);
        $position = Storage::disk("oss")->appendObject("stubs/file.txt", "contents", 0, ["options" => ["checkmd5" => false]]);
        $this->assertSame($position, 8);
    }
}
