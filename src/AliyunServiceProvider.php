<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AliyunMacro;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendFile;
use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AppendObject;
use Illuminate\Container\Container;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use League\Flysystem\Filesystem;

class AliyunServiceProvider extends BaseServiceProvider
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . "/../config/config.php",
            "filesystems.disks.oss"
        );

        $this->app->make("filesystem")
            ->extend("oss", function (Container $app, array $config) {
                $adapter = $app->make(AliyunFactory::class)->createAdapter($config);
                $driver = $app->make(Filesystem::class, ["adapter" => $adapter]);
                $filesystem = $app->make(FilesystemAdapter::class, ["driver" => $driver,"adapter" => $adapter,"config" => $config]);
                $this->registerMicros($config["macros"] ?? [], $filesystem, $app);
                return $filesystem;
            });
    }

    /**
     * @var string[]
     */
    protected $defaultMacroClasses = [
        AppendFile::class,
        AppendObject::class,
    ];

    /**
     * @param array $macroClasses
     * @param FilesystemAdapter $filesystemAdapter
     * @param Container $app
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerMicros(array $macroClasses, FilesystemAdapter $filesystemAdapter, Container $app): void
    {
        $macroClasses = array_merge($this->defaultMacroClasses, $macroClasses);
        foreach ($macroClasses as $macroClass) {
            $macro = $app->make($macroClass);
            if ($macro instanceof AliyunMacro) {
                $filesystemAdapter::macro($macro->name(), $macro->macro());
            }
        }
    }
}
