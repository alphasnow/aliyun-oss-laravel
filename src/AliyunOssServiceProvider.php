<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\AliyunOss\Plugins\PutFile;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliyunOssServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('aliyun-oss.php'),
            ], 'config');
        }
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'filesystems.disks.aliyun'
        );

        Storage::extend('aliyun', function (Application $app, array $config) {
            $accessId = $config['access_id'];
            $accessKey = $config['access_key'];
            $cdnDomain = empty($config['cdn_domain']) ? '' : $config['cdn_domain'];
            $bucket = $config['bucket'];
            $ssl = empty($config['ssl']) ? false : $config['ssl'];
            $isCname = empty($config['is_cname']) ? false : $config['is_cname'];
            $debug = empty($config['debug']) ? false : $config['debug'];
            $endPoint = $config['endpoint']; // 默认作为外部节点
            $epInternal = $isCname ? $cdnDomain : (empty($config['endpoint_internal']) ? $endPoint : $config['endpoint_internal']); // 内部节点

            $client = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $adapter = new AliyunOssAdapter($client, $bucket, $endPoint, $ssl, $isCname, $debug, $cdnDomain);
            $filesystem = new Filesystem($adapter);

            $filesystem->addPlugin(new PutFile());
            $filesystem->addPlugin(new PutRemoteFile());

            return $filesystem;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
