<?php

return [
    'driver' => 'aliyun',
    'access_id' => env('ALIYUN_OSS_ACCESS_ID'), // AccessKey ID, For example: LTAI4**************qgcsA
    'access_key' => env('ALIYUN_OSS_ACCESS_KEY'), // AccessKey Secret, For example: PkT4F********************Bl9or
    'bucket' => env('ALIYUN_OSS_BUCKET'), // For example: my-storage
    'endpoint' => env('ALIYUN_OSS_ENDPOINT'), // For example: oss-cn-shanghai.aliyuncs.com
    'internal' => env('ALIYUN_OSS_INTERNAL', null), // For example: oss-cn-shanghai-internal.aliyuncs.com
    'domain' => env('ALIYUN_OSS_DOMAIN', null), // For example: oss.my-domain.com
    'use_ssl' => env('ALIYUN_OSS_USE_SSL', false), // Whether to use https
    'prefix' => env('ALIYUN_OSS_PREFIX', null), // The prefix of the store path
    'security_token' => env('ALIYUN_OSS_TOKEN', null), // Used by \OSS\OssClient
    'use_domain_endpoint' => env('ALIYUN_OSS_USE_DOMAIN_ENDPOINT', false), // Whether to upload using domain
    'signature_expires' => env('ALIYUN_OSS_SIGNATURE_EXPIRES', '+60 minutes'), // The valid time of the temporary url
];
