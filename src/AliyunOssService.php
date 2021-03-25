<?php

namespace AlphaSnow\AliyunOss;

use think\Service as BaseService;
use AlphaSnow\AliyunOss\Plugins\PutRemoteFile;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use OSS\OssClient;

class AliyunOssService extends BaseService
{

    public function boot()
    {
        $appConfig = $this->app->get('config');
        if(!$appConfig->has('filesystem.disks.aliyun')){
            $filesystemConfig = $appConfig->get('filesystem');
            if($appConfig->has('aliyun-oss')){
                $filesystemConfig['disks']['aliyun'] = $appConfig->get('aliyun-oss');
            }else{
                $filesystemConfig['disks']['aliyun'] = require __DIR__.'/config/config.php';
            }
            $filesystemConfig['disks']['aliyun']['type'] = AliyunOssDriver::class;
            $appConfig->set($filesystemConfig,'filesystem');
        }

        $this->app->bind('aliyun.oss.filesystem',function(){
            $adapter = $this->app->get('aliyun.oss.adapter');

            $filesystem = new Filesystem($adapter, new Config(['disable_asserts' => true]));
            $filesystem->addPlugin(new PutRemoteFile());

            return $filesystem;
        });

        $this->app->bind('aliyun.oss.adapter',function(){
            $config = $this->app->make(AliyunOssConfig::class);
            $client = $this->app->get(OssClient::class);

            $adapter = new AliyunOssAdapter($client, $config);

            return $adapter;
        });

        $this->app->bind(AliyunOssConfig::class, function () {
            $config = $this->app->get('config')->get('aliyun-oss');
            $ossConfig = new AliyunOssConfig($config);
            $ossConfig->checkRequired();
            return $ossConfig;
        });

        $this->app->bind(OssClient::class, function () {
            $ossConfig = $this->app->get(AliyunOssConfig::class);
            $ossClient = new OssClient(
                $ossConfig->getAccessId(),
                $ossConfig->getAccessKey(),
                $ossConfig->getOssEndpoint(),
                $ossConfig->isCname(),
                $ossConfig->getSecurityToken(),
                $ossConfig->getRequestProxy()
            );
            return $ossClient;
        });
        $this->app->bind( 'aliyun.oss.client',OssClient::class);
    }

    public function register()
    {

    }
}
