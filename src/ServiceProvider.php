<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\Flysystem\AliyunOss\Plugins\AppendContent;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use League\Flysystem\Config as FlysystemConfig;
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
            $ossConfig = new Config($config);

            $client = $app->make(OssClient::class, $ossConfig->getOssClientParameters());
            $client->setUseSSL($ossConfig->get('use_ssl', false));
            return $client;
        });


        $this->app->bind('aliyun-oss.oss-adapter', function ($app, array $config) {
            $client = $app->make('aliyun-oss.oss-client', $config);

            return new Adapter($client, new Config($config));
        });

        $this->app->bind('aliyun-oss.oss-filesystem', function ($app, array $config) {
            $adapter = $app->make('aliyun-oss.oss-adapter', $config);

            $filesystem = new Filesystem($adapter, new FlysystemConfig(['disable_asserts' => true]));
            $filesystem->addPlugin(new PutRemoteFile());
            $filesystem->addPlugin(new AppendContent());
            return $filesystem;
        });
    }
}
