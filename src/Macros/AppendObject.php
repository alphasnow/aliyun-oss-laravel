<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use AlphaSnow\Flysystem\Aliyun\AliyunException;
use AlphaSnow\LaravelFilesystem\Aliyun\OssClientAdapter;
use Closure;
use Illuminate\Filesystem\FilesystemAdapter;
use OSS\Core\OssException;

class AppendObject implements AliyunMacro
{
    /**
     * @return string
     */
    public function name(): string
    {
        return "appendObject";
    }

    /**
     * @return Closure
     */
    public function macro(): Closure
    {
        return function (string $path, string $content, int $position = 0, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            try {
                return $adapter->client()->appendObject(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $content,
                    $position,
                    $adapter->options($options)
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }
}
