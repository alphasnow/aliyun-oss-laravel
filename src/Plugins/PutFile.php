<?php


namespace AlphaSnow\AliyunOss\Plugins;

use League\Flysystem\Config;
use League\Flysystem\Plugin\AbstractPlugin;

class PutFile extends AbstractPlugin
{
    public function getMethod()
    {
        return 'putFile';
    }

    public function handle($path, $filePath, array $options = []){
        $config = new Config($options);
        if (method_exists($this->filesystem, 'getConfig')) {
            $config->setFallback($this->filesystem->getConfig());
        }

        return (bool)$this->filesystem->getAdapter()->writeFile($path, $filePath, $config);
    }
}