<?php


namespace AlphaSnow\AliyunOss\Plugins;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;

class PutRemoteFile extends AbstractPlugin
{
    public function getMethod()
    {
        return 'putRemoteFile';
    }

    /**
     * @param string $path
     * @param string $remoteUrl
     * @param array $config
     * @return bool
     */
    public function handle($path, $remoteUrl, array $config = [])
    {
        $config = new Config($config);
        if (method_exists($this->filesystem, 'getConfig')) {
            $config->setFallback($this->filesystem->getConfig());
        }

        $resource = fopen($remoteUrl, 'r');
        $status = (bool)$this->filesystem->getAdapter()->writeStream($path, $resource, $config);
        fclose($resource);
        return $status;
    }

}