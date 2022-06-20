<?php

return [
    'driver' => 'oss',
    "access_key_id" => env('OSS_ACCESS_KEY_ID'), // Required, YourAccessKeyId
    "access_key_secret" => env('OSS_ACCESS_KEY_SECRET'), // Required, YourAccessKeySecret
    "endpoint" => env('OSS_ENDPOINT'), // Required, Endpoint
    "bucket" => env('OSS_BUCKET'), // Required, Bucket
    "prefix" => env('OSS_PREFIX', ""),
    "request_proxy" => env('OSS_PROXY', null),
    "security_token" => env('OSS_TOKEN', null),
    "is_cname" => env('OSS_CNAME', false),
    "use_ssl" => env('OSS_SSL', null),
    "max_retries" => env('OSS_MAX_TRIES', null),
    "timeout" => env('OSS_TIMEOUT', null),
    "connect_timeout" => env('OSS_CONNECT_TIMEOUT', null),
    "enable_sts_in_url" => env('OSS_STS_URL', null),
    "options" => [], // For example: \OSS\OssClient::OSS_CHECK_MD5 => false
    'internal' => env('OSS_INTERNAL', null), // For example: oss-cn-shanghai-internal.aliyuncs.com
    'domain' => env('OSS_DOMAIN', null), // For example: oss.my-domain.com
    "reverse_proxy" => env('OSS_REVERSE_PROXY', false),
    'macros' => []
];
