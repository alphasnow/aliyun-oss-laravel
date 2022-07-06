<?php

namespace AlphaSnow\AliyunOss;

use AlphaSnow\Flysystem\AliyunOss\Plugins\AppendContent;
use AlphaSnow\Flysystem\AliyunOss\Plugins\AppendFile;
use AlphaSnow\Flysystem\AliyunOss\Plugins\AppendObject;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use League\Flysystem\Config as FlysystemConfig;
use League\Flysystem\Filesystem;
use OSS\OssClient;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @return void
     */
    public function boot()
    {
        $this->app->make("filesystem")
            ->extend("aliyun", function ($app, array $config) {
                $ossConfig = new Config($config);

                $ossClient = new OssClient($ossConfig->get("access_id"), $ossConfig->get("access_key"), $ossConfig->getOssEndpoint(), $ossConfig->isCName(), $ossConfig->get("security_token"), $ossConfig->get("request_proxy"));
                $ossConfig->has("use_ssl") && $ossClient->setUseSSL($ossConfig->get("use_ssl"));
                $ossConfig->has("max_retries") && $ossClient->setMaxTries($ossConfig->get("max_retries"));
                $ossConfig->has("enable_sts_in_url") && $ossClient->setSignStsInUrl($ossConfig->get("enable_sts_in_url"));
                $ossConfig->has("timeout") && $ossClient->setTimeout($ossConfig->get("timeout"));
                $ossConfig->has("connect_timeout") && $ossClient->setConnectTimeout($ossConfig->get("connect_timeout"));

                $adapter = new Adapter($ossClient, $ossConfig->get("bucket"), $ossConfig->get("prefix", ""), $ossConfig->get("options", []));
                $adapter->setOssConfig($ossConfig);

                $filesystem = new Filesystem($adapter, new FlysystemConfig(["disable_asserts" => true]));
                $filesystem->addPlugin(new AppendContent());
                $filesystem->addPlugin(new AppendObject());
                $filesystem->addPlugin(new AppendFile());

                return $filesystem;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        if (get_class($this->app) == "Laravel\Lumen\Application") {
            return;
        }

        $this->registerConfig();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @return void
     */
    protected function registerConfig()
    {
        if ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached()) {
            return;
        }

        $config = $this->app->make("config");
        $disks = $config->get("filesystems.disks", []);
        $drivers = array_column($disks, "driver");
        if (in_array("aliyun", $drivers)) {
            return;
        }

        $config->set("filesystems.disks.aliyun", array_merge(
            require __DIR__ . "/../config/config.php",
            $config->get("filesystems.disks.aliyun", [])
        ));
    }
}
