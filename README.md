# Aliyun-oss-storage for Laravel

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/utils)
[![Total Downloads](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/downloads)](https://packagist.org/packages/alphasnow/utils)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/utils)

扩展借鉴了一些优秀的代码，综合各方，同时做了更多优化，将会添加更多完善的接口和插件，打造Laravel最好的OSS Storage扩展

## 运行环境
- PHP 7.0+
- cURL extension
- Laravel 5.5+

## 安装方法
1. 如果您通过composer管理您的项目依赖，可以在你的项目根目录运行：

        $ composer require alphasnow/aliyun-oss-laravel

   或者在你的`composer.json`中声明对Aliyun OSS SDK for PHP的依赖：

        "require": {
            "alphasnow/aliyun-oss-laravel": "~1.0"
        }

   然后通过`composer install`安装依赖。composer安装完成后，在您的PHP代码中引入依赖即可：

        require_once __DIR__ . '/vendor/autoload.php';

2. 修改环境变量文件`.env`
    ```
    ALIYUN_OSS_ACCESS_ID=
    ALIYUN_OSS_ACCESS_KEY=
    ALIYUN_OSS_BUCKET=
    ALIYUN_OSS_ENDPOINT=oss-cn-shanghai.aliyuncs.com
    ALIYUN_OSS_ENDPOINT_INTERNAL=
    ALIYUN_OSS_IS_CNAME=false
    ALIYUN_OSS_CDN_DOMAIN=
    ALIYUN_OSS_IS_CNAME=false
    ALIYUN_OSS_SSL=false
    ```

3. (可选)修改配置文件 `config/filesystems.php`
    ```
    'default' => env('FILESYSTEM_DRIVER', 'aliyun'),
    // ...
    'disks'=>[
        // ...
        'aliyun' => [
            'driver' => 'aliyun',
            'access_id'  => env('ALIYUN_OSS_ACCESS_ID', ''),
            'access_key' => env('ALIYUN_OSS_ACCESS_KEY', ''),
            'bucket'     => env('ALIYUN_OSS_BUCKET', ''),
            'endpoint'   => env('ALIYUN_OSS_ENDPOINT', ''),
            'is_cname'   => env('ALIYUN_OSS_IS_CNAME', false),
            'cdn_domain' => env('ALIYUN_OSS_CDN_DOMAIN', ''),
            'ssl'        => env('ALIYUN_OSS_SSL', false),
            'debug'      => env('ALIYUN_OSS_DEBUG', false),
        ],
        // ...
    ]
    ```

## 快速使用

```
// 查询文件夹
Storage::disk('aliyun')->files($directory);
Storage::disk('aliyun')->allFiles($directory);

// 写入文件
Storage::disk('aliyun')->put('path/to/file/file.jpg', $contents); 
Storage::disk('aliyun')->putFile('path/to/file/file.jpg', 'local/path/to/local_file.jpg');

// 读取文件
Storage::disk('aliyun')->get('path/to/file/file.jpg'); 
Storage::disk('aliyun')->exists('path/to/file/file.jpg'); 
Storage::disk('aliyun')->size('path/to/file/file.jpg'); 
Storage::disk('aliyun')->lastModified('path/to/file/file.jpg');

// 读取文件夹
Storage::disk('aliyun')->directories($directory); 
Storage::disk('aliyun')->allDirectories($directory); 

// 文件操作
Storage::disk('aliyun')->copy('old/file1.jpg', 'new/file1.jpg');
Storage::disk('aliyun')->move('old/file1.jpg', 'new/file1.jpg');
Storage::disk('aliyun')->rename('path/to/file1.jpg', 'path/to/file2.jpg');

Storage::disk('aliyun')->putRemoteFile('target/path/to/file/jacob.jpg', 'http://example.com/jacob.jpg');
Storage::disk('aliyun')->url('path/to/img.jpg');

Storage::disk('aliyun')->getTemporaryUrl('path/to/img.jpg',3600);

Storage::disk('aliyun')->prepend('file.log', 'Prepended Text'); 
Storage::disk('aliyun')->append('file.log', 'Appended Text');

Storage::disk('aliyun')->delete('file.jpg');
Storage::disk('aliyun')->delete(['file1.jpg', 'file2.jpg']);

// 文件夹操作
Storage::disk('aliyun')->makeDirectory($directory); 
Storage::disk('aliyun')->deleteDirectory($directory); 
```

> [阿里云OSS文档](https://help.aliyun.com/document_detail/32099.html?spm=5176.doc31981.6.335.eqQ9dM)

## 主要参考
- [jacobcyl/ali-oss-storage](https://github.com/jacobcyl/Aliyun-oss-storage)
- [overtrue/laravel-versionable](https://github.com/overtrue/laravel-versionable)

## License
Source code is release under MIT license. Read LICENSE file for more information.
 