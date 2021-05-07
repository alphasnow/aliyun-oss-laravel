<?php

namespace AlphaSnow\AliyunOss;

use Aliyun\Flysystem\AliyunOss\AliyunOssAdapter as BaseAdapter;
use League\Flysystem\Adapter\CanOverwriteFiles;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use OSS\OssClient;

class AliyunOssAdapter extends BaseAdapter implements CanOverwriteFiles
{
    /**
     * @var AliyunOssConfig
     */
    protected $ossConfig;

    /**
     * @param OssClient $ossClient
     * @param AliyunOssConfig $ossConfig
     */
    public function __construct(OssClient $ossClient, AliyunOssConfig $ossConfig)
    {
        $this->ossConfig = $ossConfig;
        parent::__construct($ossClient, $ossConfig->get('bucket'), $ossConfig->get('prefix', null), $ossConfig->get('options', []));
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptionsFromConfig(Config $config)
    {
        $options = parent::getOptionsFromConfig($config);

        if ($visibility = $config->get('visibility')) {
            // Object ACL > Bucket ACL
            $options[OssClient::OSS_OBJECT_ACL] = $visibility === AdapterInterface::VISIBILITY_PUBLIC ? OssClient::OSS_ACL_TYPE_PUBLIC_READ : OssClient::OSS_ACL_TYPE_PRIVATE;
        }

        return $options;
    }

    /**
     * Used by \Illuminate\Filesystem\FilesystemAdapter::url
     * Get the URL for the file at the given path.
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        return $this->ossConfig->getUrlDomain().'/' . ltrim($path, '/');
    }

    /**
     * Used by \Illuminate\Filesystem\FilesystemAdapter::temporaryUrl
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getTemporaryUrl($path, $expiration, array $options = [])
    {
        $object = $this->applyPathPrefix($path);
        $clientOptions = $this->getOptionsFromConfig(new Config($options));
        $timeout = $expiration->getTimestamp() - time();

        return $this->client->signUrl($this->bucket, $object, $timeout, OssClient::OSS_HTTP_GET, $clientOptions);
    }
}
