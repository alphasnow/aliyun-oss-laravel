<?php

namespace AlphaSnow\AliyunOss\Plugins;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class PutRemoteFile
 * @package AlphaSnow\AliyunOss\Plugins
 */
class PutRemoteFile extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'putRemoteFile';
    }

    /**
     * @param string $path
     * @param string $remoteUrl
     * @param array $options
     * @return bool
     */
    public function handle($path, $remoteUrl, array $options = [])
    {
        $config = new Config($options);
        if (method_exists($this->filesystem, 'getConfig')) {
            $config->setFallback($this->filesystem->getConfig());
        }

        // Get file stream from remote url
        $resource = fopen($remoteUrl, 'r');

        return (bool)$this->filesystem->getAdapter()->writeStream($path, $resource, $config);
    }
}
