<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\AliyunOss\Plugins\PutFile;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory as FactoryContract;
use Illuminate\Support\ServiceProvider;
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

        $this->app->make(FactoryContract::class)
            ->extend('aliyun', function (Application $app, array $config) {
                $config = new AliyunOssConfig($config);
                $config->checkRequired();

                $client = new OssClient(
                    $config->getAccessId(),
                    $config->getAccessKey(),
                    $config->getOssEndpoint(),
                    $config->isCname(),
                    $config->getSecurityToken(),
                    $config->getRequestProxy()
                );

                $adapter = new AliyunOssAdapter($client, $config);
                $filesystem = new Filesystem($adapter);
                $filesystem->addPlugin(new PutFile());
                $filesystem->addPlugin(new PutRemoteFile());

                return $filesystem;
            });
    }
}
