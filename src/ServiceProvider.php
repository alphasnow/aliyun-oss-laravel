<?php

namespace AlphaSnow\AliyunOss;

use Aliyun\Flysystem\AliyunOss\Plugins\PutFile;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use OSS\OssClient;

/**
 * Class ServiceProvider
 * @package AlphaSnow\AliyunOss
 */
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
                return $app->make('aliyun-oss.oss-filesystem', $config);
            });
    }

    public function register()
    {
        $this->app->bind('aliyun-oss.oss-client', function ($app, array $config) {
            $ossConfig = new AliyunOssConfig($config);
            $client = $app->make(OssClient::class, $ossConfig->getOssClientParameters());
            $client->setUseSSL($ossConfig->get('use_ssl', false));
            return $client;
        });

        $this->app->bind('aliyun-oss.oss-filesystem', function ($app, array $config) {
            $client = $app->make('aliyun-oss.oss-client', $config);
            $adapter = new AliyunOssAdapter($client, new AliyunOssConfig($config));
            $filesystem = new Filesystem($adapter, new Config(['disable_asserts' => true]));
            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());
            return $filesystem;
        });

        $this->app->singleton('aliyun-oss.client', function ($app) {
            $config = $app->get('config')->get('filesystems.disks.aliyun');
            return $app->make('aliyun-oss.oss-client', $config);
        });
    }
}
