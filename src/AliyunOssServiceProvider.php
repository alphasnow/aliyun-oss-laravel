<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\AliyunOss\Plugins\PutFile;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use OSS\OssClient;

/**
 * Class AliyunOssServiceProvider
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
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
                $config = $app->get(AliyunOssConfig::class);

                $adapter = new AliyunOssAdapter($client, $config);
                $filesystem = new Filesystem($adapter, new Config(['disable_asserts' => true]));
                $filesystem->addPlugin(new PutFile());
                $filesystem->addPlugin(new PutRemoteFile());

                return $filesystem;
            });
    }

    public function register()
    {
        $this->app->singleton(AliyunOssConfig::class, function ($app) {
            $config = $app->get('config')->get('filesystems.disks.aliyun');
            $ossConfig = new AliyunOssConfig($config);
            $ossConfig->checkRequired();
            return $ossConfig;
        });

        $this->app->singleton(OssClient::class, function ($app) {
            $ossConfig = $app->get(AliyunOssConfig::class);
            $ossClient = new OssClient(
                $ossConfig->getAccessId(),
                $ossConfig->getAccessKey(),
                $ossConfig->getOssEndpoint(),
                $ossConfig->isCname(),
                $ossConfig->getSecurityToken(),
                $ossConfig->getRequestProxy()
            );
            return $ossClient;
        });
        $this->app->alias(OssClient::class, 'aliyun.oss.client');
    }
}
