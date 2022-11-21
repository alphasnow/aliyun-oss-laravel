<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use AlphaSnow\Flysystem\Aliyun\AliyunAdapter as BaseAdapter;

class AliyunAdapter extends BaseAdapter
{
    public function getUrl(string $path): string
    {
        // After Laravel v9.33.0 (#44330), path are prefixed
        $usePrefix = $this->config["use_url_prefix"] ?? false;
        if ($usePrefix === false) {
            return $this->urlGenerator->fullUrl($path);
        }
        return parent::getUrl($path);
    }
}
