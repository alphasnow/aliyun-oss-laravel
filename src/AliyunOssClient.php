<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \OSS\OssClient
 */
class AliyunOssClient extends BaseFacade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aliyun-oss.client';
    }
}
