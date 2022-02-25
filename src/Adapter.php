<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter as BaseAdapter;
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
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getUrl(string $path): string
    {
        $object = $this->prefixer->prefixPath($path);
        return $this->ossConfig->getUrlDomain() . '/' . ltrim($object, '/');
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param  string  $path
     * @param \DateTimeInterface|null $expiration
     * @param  array  $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getTemporaryUrl(string $path, \DateTimeInterface $expiration = null, array $options = []): string
    {
        $object = $this->prefixer->prefixPath($path);
        $clientOptions = $this->options->mergeConfig(new FlysystemConfig($options), $this->visibility);

        if (is_null($expiration)) {
            $expiration = new \DateTime($this->ossConfig->get('signature_expires'));
        }
        $timeout = $expiration->getTimestamp() - (new \DateTime('now'))->getTimestamp();

        $url = $this->client->signUrl($this->bucket, $object, $timeout, OssClient::OSS_HTTP_GET, $clientOptions);
        return $this->ossConfig->correctUrl($url);
    }
}
