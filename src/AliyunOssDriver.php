<?php

namespace AlphaSnow\AliyunOss;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Adapter\Local as LocalAdapter;
use think\filesystem\Driver;
use think\Container;

class AliyunOssDriver extends Driver
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [
        'root' => '',
    ];

    protected function createAdapter(): AdapterInterface
    {
        return Container::getInstance()->get('aliyun.oss.adapter');
//        $permissions = $this->config['permissions'] ?? [];
//
//        $links = ($this->config['links'] ?? null) === 'skip'
//            ? LocalAdapter::SKIP_LINKS
//            : LocalAdapter::DISALLOW_LINKS;
//
//        return new LocalAdapter(
//            $this->config['root'],
//            LOCK_EX,
//            $links,
//            $permissions
//        );
    }
}
