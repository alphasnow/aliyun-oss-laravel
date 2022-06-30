<?php

namespace AlphaSnow\AliyunOss\Tests;

use Illuminate\Filesystem\FilesystemAdapter;

class FilesystemTest extends TestCase
{
    public function filesystemProvider()
    {
        $this->setUpTheTestEnvironment();

        $filesystem = $this->app->make("filesystem")->disk("aliyun");
        return [
          [$filesystem]
        ];
    }


    /**
     * @param FilesystemAdapter $filesystem
     * @dataProvider filesystemProvider
     */
    public function testUrl($filesystem)
    {
        $url = $filesystem->url("foo/bar.txt");

        $this->assertSame("http://bucket.endpoint.com/foo/bar.txt", $url);
    }

    /**
     * @param FilesystemAdapter $filesystem
     * @dataProvider filesystemProvider
     */
    public function testTemporaryUrl($filesystem)
    {
        $expiration = new \DateTime("+30 minutes");
        $url = $filesystem->temporaryUrl("foo/bar.txt", $expiration);

        $preg = "/http:\/\/bucket.endpoint.com\/foo\/bar.txt\?OSSAccessKeyId=access_id&Expires=\d{10}&Signature=.+/";
        $this->assertSame(1, preg_match($preg, $url));
    }
}
