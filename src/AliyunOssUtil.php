<?php

namespace AlphaSnow\AliyunOss;

use League\Flysystem\Config;
use OSS\OssClient;

class AliyunOssUtil
{
    public static $resultMap = [
        'Body' => 'raw_contents',
        'Content-Length' => 'size',
        'ContentType' => 'mimetype',
        'Size' => 'size',
        'StorageClass' => 'storage_class',
    ];
    /**
     * @var array
     */
    public static $metaOptions = [
        'CacheControl',
        'Expires',
        'ServerSideEncryption',
        'Metadata',
        'ACL',
        'ContentType',
        'ContentDisposition',
        'ContentLanguage',
        'ContentEncoding',
    ];

    /**
     * @var string[]
     */
    public static $metaMap = [
        'CacheControl' => 'Cache-Control',
        'Expires' => 'Expires',
        'ServerSideEncryption' => 'x-oss-server-side-encryption',
        'Metadata' => 'x-oss-metadata-directive',
        'ACL' => 'x-oss-object-acl',
        'ContentType' => 'Content-Type',
        'ContentDisposition' => 'Content-Disposition',
        'ContentLanguage' => 'response-content-language',
        'ContentEncoding' => 'Content-Encoding',
    ];

    public static $metaHeaders = [
        'storage',
        OssClient::OSS_ACL,
        OssClient::OSS_OBJECT_ACL,
        OssClient::OSS_OBJECT_GROUP,
        OssClient::OSS_OBJECT_COPY_SOURCE,
        OssClient::OSS_OBJECT_COPY_SOURCE_RANGE,
        OssClient::OSS_PROCESS,
        OssClient::OSS_CALLBACK,
        OssClient::OSS_CALLBACK_VAR,
        OssClient::OSS_REQUEST_PAYER,
        OssClient::OSS_TRAFFIC_LIMIT,
        OssClient::OSS_SECURITY_TOKEN
    ];

    public static function getHeadersFromConfig(Config $config)
    {
        $headers = [];
        foreach (static::$metaOptions as $option) {
            if (!$config->has($option)) {
                continue;
            }
            $headers[static::$metaMap[$option]] = $config->get($option);
        }

        foreach (static::$metaHeaders as $header) {
            if (!$config->has($header)) {
                continue;
            }
            $headers[$header] = $config->get($header);
        }

        return $headers;
    }
}
