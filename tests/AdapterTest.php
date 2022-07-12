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
    public function test_methods()
    {
        $adapter = new OssClientAdapter(Storage::disk("oss"));
        $client = $adapter->client();
        $this->assertInstanceOf(OssClient::class, $client);

        $bucket = $adapter->bucket();
        $this->assertSame("bucket", $bucket);

        $path = $adapter->path("foo/bar.jpg");
        $this->assertSame("tests/foo/bar.jpg", $path);

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
