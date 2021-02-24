<?php

/*
 * This file is part of the alphasnow/aliyun-oss-laravel.
 * (c) alphasnow <wind91@foxmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AlphaSnow\AliyunOss;

use AlphaSnow\AliyunOss\Plugins\PutFile;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliyunOssServiceProvider extends ServiceProvider
{
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config.php');
        $this->mergeConfigFrom($source, 'filesystems.disks.aliyun');
    }

    public function boot()
    {
        $this->setupConfig();

        $this->app->make('filesystem')->extend('aliyun', function ($app, $config) {
            $client = $app->make(OssClient::class);
            $option = new OssOption();
            $adapter = new AliyunOssAdapter($config,$client,$option);
            $filesystem =  new Filesystem($adapter);
            // $filesystem->addPlugin(new PutFile());

            return $filesystem;
        });
    }

    public function register()
    {
        $this->app->singleton(OssClient::class, function ($app){
            $config = $app['config']->get('filesystems.disks.aliyun');
            return new OssClient($config['access_id'], $config['access_key'], $config['endpoint'], $config['is_cname']);
        });
    }

    public function provides()
    {
        return [
            OssClient::class
        ];
    }
}
