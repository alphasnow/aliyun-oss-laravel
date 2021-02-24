<?php

/*
 * This file is part of the alphasnow/aliyun-oss-laravel.
 * (c) alphasnow <wind91@foxmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlphaSnow\AliyunOss;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use OSS\OssClient;

class AliyunOssAdapter extends AbstractAdapter
{
    /**
     * @var OssClient
     */
    protected $client;

    protected $config;
    protected $option;

    public function __construct(array $config,OssClient $client,OssOption $option)
    {
        $this->client = $client;
        $this->config = $config;
        $this->option = $option;
    }

    public function write($path, $contents, Config $config)
    {
        // TODO: Implement write() method.
        $object = $this->applyPathPrefix($path);
        $options = $this->option->parseConfig($config);
        $this->client->putObject($this->config['bucket'], $object, $contents, $options);
        // todo: return array
        return true;
    }

    public function writeStream($path, $resource, Config $config)
    {
        // TODO: Implement writeStream() method.
    }

    public function update($path, $contents, Config $config)
    {
        // TODO: Implement update() method.
    }

    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }

    public function rename($path, $newpath)
    {
        // TODO: Implement rename() method.
    }

    public function copy($path, $newpath)
    {
        // TODO: Implement copy() method.
    }

    public function delete($path)
    {
        // TODO: Implement delete() method.
    }

    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }

    public function has($path)
    {
        // TODO: Implement has() method.
    }

    public function read($path)
    {
        // TODO: Implement read() method.
    }

    public function readStream($path)
    {
        // TODO: Implement readStream() method.
    }

    public function listContents($directory = '', $recursive = false)
    {
        // TODO: Implement listContents() method.
    }

    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }

    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }

    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }


}
