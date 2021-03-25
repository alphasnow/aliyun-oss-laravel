<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Class AliyunOssClient
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssClient extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return 'aliyun-oss.client';
    }
}
