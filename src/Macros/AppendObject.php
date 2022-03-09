<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use AlphaSnow\Flysystem\Aliyun\AliyunException;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Config;
use OSS\Core\OssException;
use Closure;

class AppendObject implements AliyunMacro
{
    public function name(): string
    {
        return "appendObject";
    }

    public function macro(): Closure
    {
        return function (string $path, string $content, int $position = 0, array $options = []) {
            try {
                /**
                 * @var FilesystemAdapter $this
                 */
                return $this->getAdapter()->getClient()->appendObject(
                    $this->getAdapter()->getBucket(),
                    $this->getAdapter()->getPrefixer()->prefixPath($path),
                    $content,
                    $position,
                    $this->getAdapter()->getOptions()->mergeConfig(new Config($options))
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }
}
