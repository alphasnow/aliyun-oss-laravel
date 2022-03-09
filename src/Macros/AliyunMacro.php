<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Macros;

use Closure;

interface AliyunMacro
{
    public function name(): string;

    public function macro(): Closure;
}
