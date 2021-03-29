<?php

namespace AlphaSnow\AliyunOss\Tests;

use OSS\OssClient;

class FacadeTest extends TestCase
{
    public function testMethod()
    {
        $client = \AlphaSnow\AliyunOss\AliyunOssClient::getFacadeRoot();

        $this->assertInstanceOf(OssClient::class, $client);
    }

    public function testRootClass()
    {
        $client = \AliyunOssClient::getFacadeRoot();

        $this->assertInstanceOf(OssClient::class, $client);
    }
}
