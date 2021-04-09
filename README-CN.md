[English](README.md) | 简体中文  

# Aliyun Oss Storage for Laravel

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Build Status](https://travis-ci.com/alphasnow/aliyun-oss-laravel.svg?branch=master)](https://travis-ci.com/alphasnow/aliyun-oss-laravel)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![Build Status](https://github.com/alphasnow/aliyun-oss-laravel/workflows/CI/badge.svg)](https://github.com/alphasnow/aliyun-oss-laravel/actions)

[aliyun-oss-laravel](https://alphasnow.github.io/aliyun-oss-laravel/) 打造 Laravel 最好的 OSS Storage 扩展

## 环境要求
- PHP >= 7.0
- laravel/framework >= 5.5

## 安装依赖
1. 通过composer管理您的项目依赖，可以在你的项目根目录运行：  
    ```
    $ composer require alphasnow/aliyun-oss-laravel
    ```
    或者在你的`composer.json`中声明依赖：  
    ```
    "require": {
        "alphasnow/aliyun-oss-laravel": "~2.0"
    }
    ```
    然后通过`composer install`安装依赖。  

2. 修改环境配置 `.env`
    ```
    ALIYUN_OSS_ACCESS_ID = <Your aliyun accessKeyId, Required>
    ALIYUN_OSS_ACCESS_KEY= <Your aliyun accessKeySecret, Required>
    ALIYUN_OSS_BUCKET    = <Your oss bucket name, Required>
    ALIYUN_OSS_ENDPOINT  = <Your oss endpoint domain, Required>
    ALIYUN_OSS_IS_CNAME  = false
    ALIYUN_OSS_CDN_DOMAIN= <Your cdn domain, Optional>
    ALIYUN_OSS_IS_SSL    = false
    ```

3. (可选) 修改文件配置 `config/filesystems.php`
    ```
    'default' => env('FILESYSTEM_DRIVER', 'aliyun'),
    // ...
    'disks'=>[
        // ...
        'aliyun' => [
            'driver'     => 'aliyun',
            'access_id'  => env('ALIYUN_OSS_ACCESS_ID'),
            'access_key' => env('ALIYUN_OSS_ACCESS_KEY'),
            'bucket'     => env('ALIYUN_OSS_BUCKET'),
            'endpoint'   => env('ALIYUN_OSS_ENDPOINT', 'oss-cn-shanghai.aliyuncs.com'),
            'is_cname'   => env('ALIYUN_OSS_IS_CNAME', false),
            'cdn_domain' => env('ALIYUN_OSS_CDN_DOMAIN', ''),
            'is_ssl'     => env('ALIYUN_OSS_IS_SSL', false),
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
Storage::disk('aliyun')->putFile('prefix/path', '/local/path/file.txt');
Storage::disk('aliyun')->putFileAs('prefix/path', '/local/path/file.txt', 'file.txt');

Storage::disk('aliyun')->put('prefix/path/file.txt', file_get_contents('/local/path/file.txt'));
$fp = fopen('/local/path/file.txt','r');
Storage::disk('aliyun')->put('prefix/path/file.txt', $fp);
fclose($fp);

Storage::disk('aliyun')->putRemoteFile('prefix/path/file.txt', 'http://example.com/file.txt');

Storage::disk('aliyun')->prepend('prefix/path/file.txt', 'Prepend Text'); 
Storage::disk('aliyun')->append('prefix/path/file.txt', 'Append Text');
```

#### 读取
```php
Storage::disk('aliyun')->url('prefix/path/file.txt');
Storage::disk('aliyun')->temporaryUrl('prefix/path/file.txt', \Carbon\Carbon::now()->addMinutes(30));

Storage::disk('aliyun')->get('prefix/path/file.txt'); 

Storage::disk('aliyun')->exists('prefix/path/file.txt'); 
Storage::disk('aliyun')->size('prefix/path/file.txt'); 
Storage::disk('aliyun')->lastModified('prefix/path/file.txt');
```

#### 删除
```php
Storage::disk('aliyun')->delete('prefix/path/file.txt');
Storage::disk('aliyun')->delete(['prefix/path/file1.txt', 'prefix/path/file2.txt']);
```

#### 文件操作
```php
Storage::disk('aliyun')->copy('prefix/path/file.txt', 'prefix/path/file_new.txt');
Storage::disk('aliyun')->move('prefix/path/file.txt', 'prefix/path/file_new.txt');
Storage::disk('aliyun')->rename('prefix/path/file.txt', 'prefix/path/file_new.txt');
```

#### 文件夹操作
```php
Storage::disk('aliyun')->makeDirectory('prefix/path'); 
Storage::disk('aliyun')->deleteDirectory('prefix/path');

Storage::disk('aliyun')->files('prefix/path');
Storage::disk('aliyun')->allFiles('prefix/path');

Storage::disk('aliyun')->directories('prefix/path'); 
Storage::disk('aliyun')->allDirectories('prefix/path'); 
```

## 文档
- [对象存储 OSS-阿里云](https://help.aliyun.com/product/31815.html)

## 问题
如使用中遇到问题，[提交 Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## 许可证
[MIT](LICENSE)