<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Aliyun\Flysystem\AliyunOss\Plugins\PutFile;
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
                $client = $this->makeOssClient($app, $config);
                $adapter = new AliyunOssAdapter($client, $config);
                $filesystem = new Filesystem($adapter, new Config(['disable_asserts' => true]));
                $filesystem->addPlugin(new PutFile());
                return $filesystem;
            });
    }

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param array $config
     * @return OssClient
     */
    protected function makeOssClient($app, $config)
    {
        return $app->make(OssClient::class, [
            'accessKeyId' => $config['accessId'],
            'accessKeySecret' => $config['accessKey'],
            'endpoint' => $config['endpoint'],
            'isCName' => $config['isCname'],
            'securityToken' => $config['securityToken']
        ]);
    }

    public function register()
    {
        $this->app->singleton('aliyun-oss.client', function ($app) {
            $config = $app->get('config')->get('filesystems.disks.aliyun');
            return $this->makeOssClient($app, $config);
        });
    }
}
