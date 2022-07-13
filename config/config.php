<?php

return [
    "driver" => "oss",
    "access_key_id" => env("OSS_ACCESS_KEY_ID"), // Required, YourAccessKeyId
    "access_key_secret" => env("OSS_ACCESS_KEY_SECRET"), // Required, YourAccessKeySecret
    "endpoint" => env("OSS_ENDPOINT"), // Required, Endpoint
    "bucket" => env("OSS_BUCKET"), // Required, Bucket
    "prefix" => env("OSS_PREFIX", ""), // For example: user/uploads
    "request_proxy" => env("OSS_PROXY", null), // Used by \OSS\OssClient
    "security_token" => env("OSS_TOKEN", null), // Used by \OSS\OssClient
    "is_cname" => env("OSS_CNAME", false), // If this is the CName and binded in the bucket.
    "use_ssl" => env("OSS_SSL", null), // Whether to use HTTPS
    "max_retries" => env("OSS_MAX_TRIES", null), // Sets the max retry count
    "timeout" => env("OSS_TIMEOUT", null), // The request timeout time
    "connect_timeout" => env("OSS_CONNECT_TIMEOUT", null), // The connection timeout time
    "enable_sts_in_url" => env("OSS_STS_URL", null), // Enable/disable STS in the URL
    "internal" => env("OSS_INTERNAL", null), // For example: oss-cn-shanghai-internal.aliyuncs.com
    "domain" => env("OSS_DOMAIN", null), // For example: oss.my-domain.com
    "reverse_proxy" => env("OSS_REVERSE_PROXY", false), // Whether to use the Reverse proxy, such as nginx
    "options" => [], // For example: [\OSS\OssClient::OSS_CHECK_MD5 => false]
    "macros" => [] // For example: [\App\Macros\ListBuckets::class,\App\Macros\CreateBucket::class]
];
