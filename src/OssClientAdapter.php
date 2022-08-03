<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use AlphaSnow\Flysystem\Aliyun\AliyunException;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Config;

class OssClientAdapter
{
    /**
     * @var AliyunAdapter
     */
    protected $adapter;

    /**
     * @param FilesystemAdapter $filesystemAdapter
     */
    public function __construct(FilesystemAdapter $filesystemAdapter)
    {
        $adapter = $filesystemAdapter->getAdapter();
        if (!$adapter instanceof AliyunAdapter) {
            throw new AliyunException("Adapter expect AliyunAdapter, But got ".$adapter::class, 0);
        }
        $this->adapter = $adapter;
    }

    /**
     * @return \OSS\OssClient
     */
    public function client()
    {
        return $this->adapter->getClient();
    }

    /**
     * @return string
     */
    public function bucket()
    {
        return $this->adapter->getBucket();
    }

    /**
     * @param string $path
     * @return string
     */
    public function path($path = "")
    {
        return $this->adapter->getPrefixer()->prefixPath($path);
    }

    /**
     * @param array $options
     * @return array
     */
    public function options($options = [])
    {
        return $this->adapter->getOptions()->mergeConfig(new Config($options));
    }
}
