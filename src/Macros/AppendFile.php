<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use AlphaSnow\Flysystem\Aliyun\AliyunException;
use AlphaSnow\LaravelFilesystem\Aliyun\OssClientAdapter;
use Closure;
use Illuminate\Filesystem\FilesystemAdapter;
use OSS\Core\OssException;

class AppendFile implements AliyunMacro
{
    /**
     * @return string
     */
    public function name(): string
    {
        return "appendFile";
    }

    /**
     * @return Closure
     */
    public function macro(): Closure
    {
        return function (string $path, string $file, int $position = 0, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            try {
                return $adapter->client()->appendFile(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $file,
                    $position,
                    $adapter->options($options)
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }
}
