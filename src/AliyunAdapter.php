<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter as BaseAdapter;
use League\Flysystem\Config as FlysystemConfig;
use OSS\OssClient;

/**
 * Class Adapter
 */
class AliyunAdapter extends BaseAdapter
{
    /**
     * @var AliyunConfig
     */
    protected $config;

    /**
     * @param OssClient $ossClient
     * @param AliyunConfig $ossConfig
     */
    public function __construct(OssClient $ossClient, AliyunConfig $aliyunConfig)
    {
        $this->config = $aliyunConfig;
        parent::__construct($ossClient, $this->config->get('bucket'), $this->config->get('prefix', ""), $this->config->get('options', []));
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
        return $this->config->getDomain() . '/' . ltrim($object, '/');
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
        $options = $this->options->mergeConfig(new FlysystemConfig($options));

        if (is_null($expiration)) {
            $timeout = intval($this->config->get('signature_expires'));
        } else {
            $timeout = $expiration->getTimestamp() - (new \DateTime())->getTimestamp();
        }

        $url = $this->client->signUrl($this->bucket, $object, $timeout, OssClient::OSS_HTTP_GET, $options);
        return $this->config->correctUrl($url);
    }
}
