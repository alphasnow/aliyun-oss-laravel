<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AliyunMacro;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendFile;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendObject;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliyunServiceProvider extends BaseServiceProvider
{
    private $defaultMacros = [
        AppendObject::class,
        AppendFile::class,
    ];

    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'filesystems.disks.oss'
        );

        $this->app->make('filesystem')
            ->extend('oss', function ($app, array $config) {
                $aliyunConfig = new AliyunConfig($config);

                $ossClient = new OssClient($aliyunConfig->get('access_key_id'), $aliyunConfig->get('access_key_secret'), $aliyunConfig->getRequestEndpoint(), $aliyunConfig->get('is_cname', false), $aliyunConfig->get('security_token', null), $aliyunConfig->get('request_proxy', null));
                $aliyunConfig->get("use_ssl") && $ossClient->setUseSSL($config["use_ssl"]);
                $aliyunConfig->get("max_retries") && $ossClient->setMaxTries($config["max_retries"]);
                $aliyunConfig->get("enable_sts_in_url") && $ossClient->setSignStsInUrl($config["enable_sts_in_url"]);
                $aliyunConfig->get("timeout") && $ossClient->setTimeout($config["timeout"]);
                $aliyunConfig->get("connect_timeout") && $ossClient->setConnectTimeout($config["connect_timeout"]);

                $aliyunAdapter = new AliyunAdapter($ossClient, $aliyunConfig);
                $filesystemAdapter = new FilesystemAdapter(new Filesystem($aliyunAdapter), $aliyunAdapter, $config);

                $macros = array_merge($this->defaultMacros, $aliyunConfig->get('macros', []));
                foreach ($macros as $macro) {
                    $aliyunMacro = $app->make($macro);
                    if ($aliyunMacro instanceof AliyunMacro) {
                        $filesystemAdapter::macro($aliyunMacro->name(), $aliyunMacro->macro());
                    }
                }
                return $filesystemAdapter;
            });
    }
}
