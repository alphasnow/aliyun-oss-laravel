<?php

return [
    'driver' => 'aliyun',
    'access_id' => env('ALIYUN_OSS_ACCESS_ID'),
    'access_key' => env('ALIYUN_OSS_ACCESS_KEY'),
    'bucket' => env('ALIYUN_OSS_BUCKET'),
    'endpoint' => env('ALIYUN_OSS_ENDPOINT', 'oss-cn-shanghai.aliyuncs.com'), // OSS 外网节点或自定义外部域名
    // 'endpoint_internal' => env('ALIYUN_OSS_ENDPOINT_INTERNAL','oss-cn-shanghai-internal.aliyuncs.com'), // v2.0.4 新增配置属性，如果为空，则默认使用 endpoint 配置(由于内网上传有点小问题未解决，请大家暂时不要使用内网节点上传，正在与阿里技术沟通中)
    'endpoint_internal' => env('ALIYUN_OSS_ENDPOINT_INTERNAL', ''),
    'cdn_domain' => env('ALIYUN_OSS_CDN_DOMAIN', ''), // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
    'is_cname' => env('ALIYUN_OSS_IS_CNAME', false), // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
    'ssl' => env('ALIYUN_OSS_SSL', false), // true to use 'https://' and false to use 'http://'. default is false,
    'debug' => env('ALIYUN_OSS_DEBUG', false),
];
