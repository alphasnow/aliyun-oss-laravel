<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'filesystems.disks.aliyun'
        );

        $this->app->make('filesystem')
            ->extend('aliyun', function ($app, array $config) {
                $ossConfig = new Config($config);
                $ossClient = $app->make(OssClient::class, $ossConfig->getOssClientParameters());
                $ossClient->setUseSSL($ossConfig->get('use_ssl', false));

                $ossAdapter = new Adapter($ossClient, new Config($config));

                $filesystem = new FilesystemAdapter(new Filesystem($ossAdapter), $ossAdapter, $config);
                return $filesystem;
            });
    }
}
