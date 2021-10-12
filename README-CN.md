[English](README.md) | 简体中文  

# Aliyun Oss Storage for Laravel
Laravel 的阿里云对象存储 Storage 扩展

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Code Coverage](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![Build Status](https://github.com/alphasnow/aliyun-oss-laravel/workflows/CI/badge.svg)](https://github.com/alphasnow/aliyun-oss-laravel/actions)
[![Build Status](https://travis-ci.com/alphasnow/aliyun-oss-laravel.svg?branch=master)](https://travis-ci.com/alphasnow/aliyun-oss-laravel)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_shield)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)

这个包是封装 [aliyun-oss-flysystem](https://github.com/alphasnow/aliyun-oss-flysystem) 到 Laravel 来作为 Storage 使用.

## 环境要求
- PHP >= 7.0
- laravel/framework >= 5.5

## 安装依赖
1. 通过composer管理您的项目依赖，可以在你的项目根目录运行：  
    ```
    $ composer require alphasnow/aliyun-oss-laravel
    ```
    然后通过`composer install`安装依赖。  

2. 修改环境配置 `.env`
    ```
    ALIYUN_OSS_ACCESS_ID = <Your aliyun accessKeyId, Required>
    ALIYUN_OSS_ACCESS_KEY= <Your aliyun accessKeySecret, Required>
    ALIYUN_OSS_BUCKET    = <Your oss bucket name, Required>
    ALIYUN_OSS_ENDPOINT  = <Your oss endpoint domain, Required>
    ```

3. (可选) 修改文件配置 `config/filesystems.php`
    ```
    'default' => env('FILESYSTEM_DRIVER', 'aliyun'),
    // ...
    'disks'=>[
        // ...
        'aliyun' => [
            'driver'     => 'aliyun',
            'access_id'  => env('ALIYUN_OSS_ACCESS_ID'),      // AccessKey ID, For example: LTAI4**************qgcsA
            'access_key' => env('ALIYUN_OSS_ACCESS_KEY'),     // AccessKey Secret, For example: PkT4F********************Bl9or
            'bucket'     => env('ALIYUN_OSS_BUCKET'),         // For example: my-storage
            'endpoint'   => env('ALIYUN_OSS_ENDPOINT'),       // For example: oss-cn-shanghai.aliyuncs.com
            'internal'   => env('ALIYUN_OSS_INTERNAL', null), // For example: oss-cn-shanghai-internal.aliyuncs.com
            'domain'     => env('ALIYUN_OSS_DOMAIN', null),   // For example: oss.my-domain.com
            'use_ssl'    => env('ALIYUN_OSS_USE_SSL', false), // Whether to use https
            'prefix'     => env('ALIYUN_OSS_PREFIX', null),   // The prefix of the store path
        ],
        // ...
    ]
    ```

## 快速使用
```php
use Illuminate\Support\Facades\Storage;
$storage = Storage::disk('aliyun');
```
#### 写入
```php
Storage::disk('aliyun')->putFile('dir/path', '/local/path/file.txt');
Storage::disk('aliyun')->putFileAs('dir/path', '/local/path/file.txt', 'file.txt');

Storage::disk('aliyun')->put('dir/path/file.txt', file_get_contents('/local/path/file.txt'));
$fp = fopen('/local/path/file.txt','r');
Storage::disk('aliyun')->put('dir/path/file.txt', $fp);
fclose($fp);

Storage::disk('aliyun')->prepend('dir/path/file.txt', 'Prepend Text'); 
Storage::disk('aliyun')->append('dir/path/file.txt', 'Append Text');

Storage::disk('aliyun')->put('dir/path/secret.txt', 'My secret', 'private');
Storage::disk('aliyun')->put('dir/path/download.txt', 'Download content', ["headers" => ["Content-Disposition" => "attachment; filename=file.txt"]]);
```

#### 读取
```php
Storage::disk('aliyun')->url('dir/path/file.txt');
Storage::disk('aliyun')->temporaryUrl('dir/path/file.txt');
Storage::disk('aliyun')->temporaryUrl('dir/path/file.txt', \Carbon\Carbon::now()->addMinutes(30));

Storage::disk('aliyun')->get('dir/path/file.txt'); 

Storage::disk('aliyun')->exists('dir/path/file.txt'); 
Storage::disk('aliyun')->size('dir/path/file.txt'); 
Storage::disk('aliyun')->lastModified('dir/path/file.txt');
```

#### 删除
```php
Storage::disk('aliyun')->delete('dir/path/file.txt');
Storage::disk('aliyun')->delete(['dir/path/file1.txt', 'dir/path/file2.txt']);
```

#### 文件操作
```php
Storage::disk('aliyun')->copy('dir/path/file.txt', 'dir/path/file_new.txt');
Storage::disk('aliyun')->move('dir/path/file.txt', 'dir/path/file_new.txt');
Storage::disk('aliyun')->rename('dir/path/file.txt', 'dir/path/file_new.txt');
```

#### 文件夹操作
```php
Storage::disk('aliyun')->makeDirectory('dir/path'); 
Storage::disk('aliyun')->deleteDirectory('dir/path');

Storage::disk('aliyun')->files('dir/path');
Storage::disk('aliyun')->allFiles('dir/path');

Storage::disk('aliyun')->directories('dir/path'); 
Storage::disk('aliyun')->allDirectories('dir/path'); 
```

#### 使用 Plugin
```php
Storage::disk('aliyun')->appendContent('dir/path/news.txt', 'The first line paragraph.', 0);
Storage::disk('aliyun')->appendContent('dir/path/news.txt', 'The second line paragraph.', 25);
Storage::disk('aliyun')->appendContent('dir/path/news.txt', 'The last line paragraph.', 51);
```

#### 使用 OssClient
```php
$adapter = Storage::disk('aliyun')->getAdapter(); 
$client = $adapter->getClient();
$client->appendObject($adapter->getBucket(), $adapter->applyPathPrefix('dir/path/file.txt'), 'Append Text', 0);
```

## 文档
- [对象存储 OSS-阿里云](https://help.aliyun.com/product/31815.html)

## 问题
如使用中遇到问题，[提交 Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## 许可证
[MIT](LICENSE)