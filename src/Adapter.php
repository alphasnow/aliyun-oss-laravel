<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\Flysystem\AliyunOss\AliyunOssAdapter as BaseAdapter;
use League\Flysystem\Config as FlysystemConfig;
use OSS\OssClient;

/**
 * Class Adapter
 */
class Adapter extends BaseAdapter
{
    /**
     * @var Config
     */
    protected $ossConfig;

    /**
     * @param OssClient $ossClient
     * @param Config $ossConfig
     */
    public function __construct(OssClient $ossClient, Config $ossConfig)
    {
        $this->ossConfig = $ossConfig;
        parent::__construct($ossClient, $ossConfig->get('bucket'), ltrim($ossConfig->get('prefix', null), '/'), $ossConfig->get('options', []));
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
        $object = $this->applyPathPrefix($path);
        return $this->ossConfig->getUrlDomain() . '/' . ltrim($object, '/');
    }

    /**
     * Used by \Illuminate\Filesystem\FilesystemAdapter::temporaryUrl
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface|null $expiration
     * @param array $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getTemporaryUrl($path, $expiration = null, array $options = [])
    {
        $object = $this->applyPathPrefix($path);
        $clientOptions = $this->getOptionsFromConfig(new FlysystemConfig($options));

        if (is_null($expiration)) {
            $expiration = new \DateTime($this->ossConfig->get('signature_expires'));
        }
        $timeout = $expiration->getTimestamp() - (new \DateTime('now'))->getTimestamp();

        $url = $this->client->signUrl($this->bucket, $object, $timeout, OssClient::OSS_HTTP_GET, $clientOptions);
        return $this->ossConfig->correctUrl($url);
    }
}
