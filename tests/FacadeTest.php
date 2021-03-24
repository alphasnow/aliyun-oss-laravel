<?php

namespace AlphaSnow\AliyunOss\Tests;

class FacadeTest extends TestCase
{
    public function testMethod()
    {
        \AliyunOssClient::setUseSSL(true);

        $this->assertTrue(\AliyunOssClient::isUseSSL());
    }
}
