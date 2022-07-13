<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use AlphaSnow\LaravelFilesystem\Aliyun\OssClientAdapter;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;

class AdapterTest extends TestCase
{
    /**
     * @test
     */
    public function use_client()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $client = $adapter->client();
        $this->assertInstanceOf(OssClient::class, $client);
    }

    /**
     * @test
     */
    public function use_bucket()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $bucket = $adapter->bucket();
        $this->assertSame("bucket", $bucket);
    }

    /**
     * @test
     */
    public function use_path()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $path = $adapter->path("foo/bar.jpg");
        $this->assertSame("tests/foo/bar.jpg", $path);
    }

    /**
     * @test
     */
    public function use_options()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $options = $adapter->options([
            "options" => ["checkmd5" => false],
            "headers" => ["Content-Disposition" => "attachment;filename=file.txt"],
            "visibility" => "private"
        ]);
        $this->assertSame([
            "checkmd5" => false,
            "headers" => [
                "Content-Disposition" => "attachment;filename=file.txt",
                "x-oss-object-acl" => "private"
            ]
        ], $options);
    }
}
