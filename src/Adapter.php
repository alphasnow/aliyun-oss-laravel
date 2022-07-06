<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\Flysystem\AliyunOss\AliyunOssAdapter as BaseAdapter;
use League\Flysystem\Config as FlysystemConfig;
use OSS\OssClient;

class Adapter extends BaseAdapter
{
    /**
     * @var Config
     */
    protected $ossConfig;

    /**
     * @param OssClient $client
     * @param string $bucket
     * @param string $prefix
     * @param array $options
     */
    public function __construct(OssClient $client, $bucket, $prefix = "", array $options = [])
    {
        parent::__construct($client, $bucket, $prefix, $options);

        $this->ossConfig = new Config(compact("bucket", "prefix", "options"));
    }

    /**
     * @param Config $ossConfig
     * @return $this
     */
    public function setOssConfig(Config $ossConfig)
    {
        $this->ossConfig = $ossConfig;

        return $this;
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

        return $this->ossConfig->getUrlDomain() . "/" . ltrim($object, "/");
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
            $expiration = new \DateTime($this->ossConfig->get("signature_expires", "+60 minutes"));
        }
        $timeout = $expiration->getTimestamp() - (new \DateTime("now"))->getTimestamp();
        $method = $options[OssClient::OSS_METHOD] ?? OssClient::OSS_HTTP_GET;

        $url = $this->client->signUrl($this->bucket, $object, $timeout, $method, $clientOptions);

        return $this->ossConfig->correctUrl($url);
    }
}
