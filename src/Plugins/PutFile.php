<?php

namespace AlphaSnow\AliyunOss\Plugins;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class PutFile
 * @package AlphaSnow\AliyunOss\Plugins
 */
class PutFile extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'putFile';
    }

    /**
     * @param string $path
     * @param string $filePath
     * @param array $options
     * @return bool
     */
    public function handle($path, $filePath, array $options = [])
    {
        $config = new Config($options);
        if (method_exists($this->filesystem, 'getConfig')) {
            $config->setFallback($this->filesystem->getConfig());
        }

        return (bool)$this->filesystem->getAdapter()->writeFile($path, $filePath, $config);
    }
}
