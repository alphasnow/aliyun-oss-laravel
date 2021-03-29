<?php

namespace AlphaSnow\AliyunOss;

use Aliyun\Flysystem\AliyunOss\AliyunOssAdapter as BaseAdapter;
use League\Flysystem\Adapter\CanOverwriteFiles;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use OSS\OssClient;

/**
 * Class AliyunOssAdapter
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssAdapter extends BaseAdapter implements CanOverwriteFiles
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(OssClient $client, array $config)
    {
        $this->config = $config;
        parent::__construct($client, $config['bucket'], $config['prefix'], $config['options']);
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
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        $url = '';

        if ($this->config['ssl']) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }

        if ($this->config['isCname']) {
            $url .= $this->config['cdnDomain'];
        } else {
            $url .= $this->config['bucket'] . '.' . $this->config['endpoint'];
        }

        $url .= '/' . ltrim($path, '/');
        return $url;
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

        return $this->client->signUrl($this->bucket, $object, $timeout, $clientOptions);
    }
}
