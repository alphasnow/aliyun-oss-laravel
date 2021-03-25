<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssClient;

class FacadeTest extends TestCase
{
    public function testMethod()
    {
        AliyunOssClient::setUseSSL(true);

        $this->assertTrue(AliyunOssClient::isUseSSL());
    }
}
