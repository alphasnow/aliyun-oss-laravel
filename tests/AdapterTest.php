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
    public function client()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $client = $adapter->client();
        $this->assertInstanceOf(OssClient::class, $client);
    }

    /**
     * @test
     */
    public function bucket()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $bucket = $adapter->bucket();
        $this->assertSame("bucket", $bucket);
    }

    /**
     * @test
     */
    public function path()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $path = $adapter->path("foo/bar.jpg");
        $this->assertSame("tests/foo/bar.jpg", $path);
    }

    /**
     * @test
     */
    public function options()
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
