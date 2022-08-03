<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use AlphaSnow\Flysystem\Aliyun\AliyunException;
use Illuminate\Filesystem\FilesystemAdapter;
use JetBrains\PhpStorm\Pure;
use League\Flysystem\Config;
use OSS\OssClient;

class OssClientAdapter
{
    /**
     * @var AliyunAdapter
     */
    protected AliyunAdapter $adapter;

    /**
     * @param FilesystemAdapter $filesystemAdapter
     */
    public function __construct(FilesystemAdapter $filesystemAdapter)
    {
        $adapter = $filesystemAdapter->getAdapter();
        if (!$adapter instanceof AliyunAdapter) {
            throw new AliyunException("OssClientAdapter construct want AliyunAdapter, But got ".$adapter::class, 0);
        }

        $this->adapter = $adapter;
    }

    /**
     * @return OssClient
     */
    #[Pure] public function client(): OssClient
    {
        return $this->adapter->getClient();
    }

    /**
     * @return string
     */
    #[Pure] public function bucket(): string
    {
        return $this->adapter->getBucket();
    }

    /**
     * @param string $path
     * @return string
     */
    #[Pure] public function path(string $path = ""): string
    {
        return $this->adapter->getPrefixer()->prefixPath($path);
    }

    /**
     * @param array $options
     * @return array
     */
    public function options(array $options = []): array
    {
        return $this->adapter->getOptions()->mergeConfig(new Config($options));
    }
}
