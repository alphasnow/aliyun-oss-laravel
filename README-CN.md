[English](README.md) | 简体中文  

# Aliyun Oss Storage for Laravel
Laravel 的阿里云对象存储 Storage 扩展

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Total Downloads](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/downloads)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Build Status](https://github.com/alphasnow/aliyun-oss-laravel/workflows/CI/badge.svg)](https://github.com/alphasnow/aliyun-oss-laravel/actions)
[![Code Coverage](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_shield)

这个包是封装 [aliyun-oss-flysystem](https://github.com/alphasnow/aliyun-oss-flysystem) 到 Laravel 来作为 Storage 使用.  
如果需要客户端直传, 可使用 Web服务端签名直传OSS扩展包 [aliyun-oss-appserver](https://github.com/alphasnow/aliyun-oss-appserver)  

## 版本兼容

| laravel      | aliyun-oss-laravel | readme |
|:-------------|:-------------------|:-------|
| \>=5.5,\<9.0 | ^3.0               | [readme](https://github.com/alphasnow/aliyun-oss-laravel/blob/3.x/README.md) |
| \>=9.0       | ^4.0               | [readme](https://github.com/alphasnow/aliyun-oss-laravel/blob/master/README.md) |

## 安装依赖
1. 通过composer管理您的项目依赖，可以在你的项目根目录运行：  
    ```bash
    composer require alphasnow/aliyun-oss-laravel
    ```
    然后通过`composer install`安装依赖。  

2. 修改环境配置 `.env`
    ```env
    OSS_ACCESS_KEY_ID=<必填, 阿里云的AccessKeyId>
    OSS_ACCESS_KEY_SECRET=<必填, 阿里云的AccessKeySecret>
    OSS_BUCKET=<必填, 对象存储的Bucket>
    OSS_ENDPOINT=<必填, 对象存储的Endpoint>
    ```

3. (可选) 修改文件配置 `config/filesystems.php`
    ```php
    "default" => env("FILESYSTEM_DRIVER", "oss"),
    // ...
    "disks"=>[
        // ...
        "oss" => [
               "driver"            => "oss",
               "access_key_id"     => env("OSS_ACCESS_KEY_ID"),           // 必填, 阿里云的AccessKeyId
               "access_key_secret" => env("OSS_ACCESS_KEY_SECRET"),       // 必填, 阿里云的AccessKeySecret
               "bucket"            => env("OSS_BUCKET"),                  // 必填, 对象存储的Bucket, 示例: my-bucket
               "endpoint"          => env("OSS_ENDPOINT"),                // 必填, 对象存储的Endpoint, 示例: oss-cn-shanghai.aliyuncs.com
               "internal"          => env("OSS_INTERNAL", null),          // 选填, 内网上传地址, 示例: oss-cn-shanghai-internal.aliyuncs.com
               "domain"            => env("OSS_DOMAIN", null),            // 选填, 绑定域名, 示例: oss.my-domain.com
               "use_ssl"           => env("OSS_SSL", false),              // 选填, 是否使用HTTPS
               "prefix"            => env("OSS_PREFIX", ""),              // 选填, 统一存储地址前缀
               "reverse_proxy"     => env("OSS_REVERSE_PROXY", false),    // 选填, 域名是否使用NGINX代理绑定
        ],
        // ...
    ]
    ```

## 快速使用
```php
use Illuminate\Support\Facades\Storage;
$storage = Storage::disk("oss");
```
#### 写入
```php
Storage::disk("oss")->putFile("dir/path", "/local/path/file.txt");
Storage::disk("oss")->putFileAs("dir/path", "/local/path/file.txt", "file.txt");

Storage::disk("oss")->put("dir/path/file.txt", file_get_contents("/local/path/file.txt"));
$fp = fopen("/local/path/file.txt","r");
Storage::disk("oss")->put("dir/path/file.txt", $fp);
fclose($fp);

Storage::disk("oss")->prepend("dir/path/file.txt", "Prepend Text"); 
Storage::disk("oss")->append("dir/path/file.txt", "Append Text");

Storage::disk("oss")->put("dir/path/secret.txt", "My secret", "private");
Storage::disk("oss")->put("dir/path/download.txt", "Download content", ["headers" => ["Content-Disposition" => "attachment; filename=download.txt"]]);
```

#### 读取
```php
Storage::disk("oss")->url("dir/path/file.txt");
Storage::disk("oss")->temporaryUrl("dir/path/file.txt", \Carbon\Carbon::now()->addMinutes(30));

Storage::disk("oss")->get("dir/path/file.txt"); 

Storage::disk("oss")->exists("dir/path/file.txt"); 
Storage::disk("oss")->size("dir/path/file.txt"); 
Storage::disk("oss")->lastModified("dir/path/file.txt");
```

#### 删除
```php
Storage::disk("oss")->delete("dir/path/file.txt");
Storage::disk("oss")->delete(["dir/path/file1.txt", "dir/path/file2.txt"]);
```

#### 文件操作
```php
Storage::disk("oss")->copy("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("oss")->move("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("oss")->rename("dir/path/file.txt", "dir/path/file_new.txt");
```

#### 文件夹操作
```php
Storage::disk("oss")->makeDirectory("dir/path"); 
Storage::disk("oss")->deleteDirectory("dir/path");

Storage::disk("oss")->files("dir/path");
Storage::disk("oss")->allFiles("dir/path");

Storage::disk("oss")->directories("dir/path"); 
Storage::disk("oss")->allDirectories("dir/path"); 
```

#### 使用宏扩展
```php
Storage::disk("oss")->appendObject("dir/path/news.txt", "The first line paragraph.", 0);
Storage::disk("oss")->appendObject("dir/path/news.txt", "The second line paragraph.", 25);
Storage::disk("oss")->appendObject("dir/path/news.txt", "The last line paragraph.", 51);

$position = Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.001", 0);
$position = Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.002", $position);
$position = Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.003", $position);
```

#### 使用 `OssClient`
```php
$client = Storage::disk("oss")->getAdapter()->getClient();
$bucketCors = $client->getBucketCors("bucket-name")
```

## 文档
- [对象存储 OSS-阿里云](https://help.aliyun.com/product/31815.html)

## 问题
如使用中遇到问题，[提交 Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## 许可证
[MIT](LICENSE)

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_large)
