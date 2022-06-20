<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use Closure;

interface AliyunMacro
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return Closure
     */
    public function macro(): Closure;
}
