<?php

namespace AlphaSnow\AliyunOss;

use think\Facade;

class AliyunOss extends Facade
{
    protected static function getFacadeClass()
    {
        return 'aliyun.oss.filesystem';
    }
}
