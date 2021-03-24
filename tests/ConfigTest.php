<?php

namespace AlphaSnow\AliyunOss\Tests;

use AlphaSnow\AliyunOss\AliyunOssConfig;
use LogicException;

class ConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = require __DIR__.'/../src/config/config.php';
        $ossConfig = new AliyunOssConfig($config);

        $this->assertSame($ossConfig->getDriver(), 'aliyun');

        $ossConfigArray = $ossConfig->toArray();
        $this->assertIsArray($ossConfigArray);
        $this->assertArrayHasKey('driver', $ossConfigArray);
    }

    public function testCheckRequired()
    {
        $this->expectException(LogicException::class);

        $this->expectExceptionMessage('Empty accessId');
        (new AliyunOssConfig([]))->checkRequired();

        $this->expectExceptionMessage('Empty accessKey');
        (new AliyunOssConfig(['accessId' => 'access_id']))->checkRequired();

        $this->expectExceptionMessage('Empty bucket');
        (new AliyunOssConfig(['accessId' => 'access_id','accessKey' => 'access_key']))->checkRequired();
    }
}
