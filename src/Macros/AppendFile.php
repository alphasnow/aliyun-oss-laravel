<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter;
use AlphaSnow\Flysystem\Aliyun\AliyunException;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Config;
use OSS\Core\OssException;
use Closure;

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
            try {
                /**
                 * @var FilesystemAdapter $this
                 * @var AliyunAdapter $adapter
                 */
                $adapter = $this->getAdapter();
                return $adapter->getClient()->appendFile(
                    $adapter->getBucket(),
                    $adapter->getPrefixer()->prefixPath($path),
                    $file,
                    $position,
                    $adapter->getOptions()->mergeConfig(new Config($options))
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }
}
