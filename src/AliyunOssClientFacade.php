<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\Facades\Facade;

class AliyunOssClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'aliyun.oss.client';
    }
}
