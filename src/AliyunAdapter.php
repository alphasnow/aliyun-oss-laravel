<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter as BaseAdapter;

class AliyunAdapter extends BaseAdapter
{
    public function getUrl(string $path): string
    {
        // After Laravel v9.33.0 (#44330), path are prefixed
        return $this->urlGenerator->fullUrl($path);
    }
}
