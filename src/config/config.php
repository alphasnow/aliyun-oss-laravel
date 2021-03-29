<?php

return [
    'driver' => 'aliyun',

    'access_id' => env('ALIYUN_OSS_ACCESS_ID'),
    'access_key' => env('ALIYUN_OSS_ACCESS_KEY'),
    'bucket' => env('ALIYUN_OSS_BUCKET'),
    'endpoint' => env('ALIYUN_OSS_ENDPOINT', 'oss-cn-shanghai.aliyuncs.com'),

    // replace with the full url of endpoint
    'is_cname' => env('ALIYUN_OSS_IS_CNAME', false),
    'cdn_domain' => env('ALIYUN_OSS_CDN_DOMAIN', ''),
    'is_ssl' => env('ALIYUN_OSS_IS_SSL', false),

    'security_token' => env('ALIYUN_OSS_TOKEN', null),
    'prefix' => env('ALIYUN_OSS_PREFIX', null),
    'options' => [],
];
