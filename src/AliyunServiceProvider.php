<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AliyunMacro;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendFile;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendObject;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class AliyunServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        if ($this->app::class == "Laravel\Lumen\Application") {
            return;
        }

        $this->registerConfig();
    }

    /**
     * @return void
     */
    protected function registerConfig()
    {
        if ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached()) {
            return;
        }

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
     */
    public function boot()
    {
        $this->app->make("filesystem")
            ->extend("oss", function (Application $app, array $config) {
                $adapter = (new AliyunFactory())->createAdapter($config);
                $driver = new Filesystem($adapter);
                $filesystem = new FilesystemAdapter($driver, $adapter, $config);
                $macros = array_merge($this->defaultMacros, $config["macros"] ?? []);
                $this->registerMicros($filesystem, $macros);
                return $filesystem;
            });
    }

    /**
     * @var array
     */
    protected $defaultMacros = [
        AppendFile::class,
        AppendObject::class,
    ];

    /**
     * @param FilesystemAdapter $filesystemAdapter
     * @param array $macros
     */
    protected function registerMicros(FilesystemAdapter $filesystemAdapter, array $macros): void
    {
        foreach ($macros as $macro) {
            if (!class_exists($macro)) {
                continue;
            }
            $aliyunMacro = new $macro();
            if (!$aliyunMacro instanceof AliyunMacro) {
                continue;
            }
            if ($filesystemAdapter->hasMacro($aliyunMacro->name())) {
                continue;
            }
            $filesystemAdapter::macro($aliyunMacro->name(), $aliyunMacro->macro());
        }
    }
}
