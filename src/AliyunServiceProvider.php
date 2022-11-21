<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class AliyunServiceProvider extends ServiceProvider
{
    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->mergeOssConfig();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    protected function mergeOssConfig()
    {
        if ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached()) {
            return;
        }

        // If a driver for OSS has been defined
        // Then configuration merge will not be performed
        $config = $this->app->make('config');
        $disks = $config->get("filesystems.disks", []);
        $drivers = array_column($disks, "driver");
        if (in_array("oss", $drivers)) {
            return;
        }

        $config->set("filesystems.disks.oss", array_merge(
            require __DIR__ . "/../config/config.php",
            $config->get("filesystems.disks.oss", [])
        ));
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->make("filesystem")
            ->extend("oss", function (Application $app, array $config) {
                $config["url_prefixed"] = version_compare($app->version(), "9.33.0", ">=");
                $client = $app->make(AliyunFactory::class)->createClient($config);
                $adapter = new AliyunAdapter($client, $config["bucket"], $config["prefix"] ?? "", $config);
                $driver = new Filesystem($adapter);
                $filesystem = new FilesystemAdapter($driver, $adapter, $config);
                (new FilesystemMacroManager($filesystem))->defaultRegister()->register($config["macros"] ?? []);
                return $filesystem;
            });
    }
}
