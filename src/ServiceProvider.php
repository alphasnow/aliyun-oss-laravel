<?php

namespace AlphaSnow\AliyunOss;

use Aliyun\Flysystem\AliyunOss\Plugins\PutFile;
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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('aliyun-oss.php'),
            ], 'config');
        }
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'filesystems.disks.aliyun'
        );

        $this->app->make('filesystem')
            ->extend('aliyun', function ($app, array $config) {
                $client = $app->get(OssClient::class);

                $adapter = new AliyunOssAdapter($config,$client);
                $filesystem = new Filesystem($adapter, new Config(['disable_asserts' => true]));
                $filesystem->addPlugin(new PutFile());

                return $filesystem;
            });
    }

    public function register()
    {
        $this->app->singleton(OssClient::class, function ($app) {
            $config = $app->get('config')->get('filesystems.disks.aliyun');
            $ossClient = new OssClient(
                $config['accessId'],
                $config['accessKey'],
                $config['endpoint'],
                $config['isCname'],
                $config['securityToken']
            );
            return $ossClient;
        });
        $this->app->alias(OssClient::class, 'aliyun.oss.client');
    }
}
