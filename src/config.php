<?php

return [
    'driver' => 'aliyun',
    'access_id'  => env('ALIYUN_OSS_ACCESS_ID', ''),
    'access_key' => env('ALIYUN_OSS_ACCESS_KEY', ''),
    'bucket'     => env('ALIYUN_OSS_BUCKET', ''),
    'endpoint'   => env('ALIYUN_OSS_ENDPOINT', ''),
    'is_cname'   => env('ALIYUN_OSS_IS_CNAME', false),
    'cdn_domain' => env('ALIYUN_OSS_CDN_DOMAIN', ''),
    'ssl'        => env('ALIYUN_OSS_SSL', false),
    'debug'      => env('ALIYUN_OSS_DEBUG', false),
];