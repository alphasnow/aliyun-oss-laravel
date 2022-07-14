English | [简体中文](README-CN.md)  

# Aliyun Oss Storage for Laravel
Alibaba Cloud Object Storage Service For Laravel

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Total Downloads](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/downloads)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Test](https://github.com/alphasnow/aliyun-oss-laravel/actions/workflows/test.yml/badge.svg)](https://github.com/alphasnow/aliyun-oss-laravel/actions/workflows/test.yml)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)

This package is a wrapper bridging [aliyun-oss-flysystem](https://github.com/alphasnow/aliyun-oss-flysystem) into Laravel as an available storage disk.

## Requirements
- PHP >= 7.0
- laravel/framework >= 5.5

## Installation
1. If you use the composer to manage project dependencies, run the following command in your project"s root directory:
    ```bash
    $ composer require alphasnow/aliyun-oss-laravel
    ```
    Then run `composer install` to install the dependency.

2. Modify the environment file `.env`
    ```
    ALIYUN_OSS_ACCESS_ID = <Your aliyun accessKeyId, Required>
    ALIYUN_OSS_ACCESS_KEY= <Your aliyun accessKeySecret, Required>
    ALIYUN_OSS_BUCKET    = <Your oss bucket name, Required>
    ALIYUN_OSS_ENDPOINT  = <Your oss endpoint domain, Required>
    ```

3. (Optional) Modify the configuration file `config/filesystems.php`
    ```
    "default" => env("FILESYSTEM_DRIVER", "aliyun"),
    // ...
    "disks"=>[
        // ...
        "aliyun" => [
            "driver"     => "aliyun",
            "access_id"  => env("ALIYUN_OSS_ACCESS_ID"),      // AccessKey ID, For example: LTAI4**************qgcsA
            "access_key" => env("ALIYUN_OSS_ACCESS_KEY"),     // AccessKey Secret, For example: PkT4F********************Bl9or
            "bucket"     => env("ALIYUN_OSS_BUCKET"),         // For example: my-storage
            "endpoint"   => env("ALIYUN_OSS_ENDPOINT"),       // For example: oss-cn-shanghai.aliyuncs.com
            "internal"   => env("ALIYUN_OSS_INTERNAL", null), // For example: oss-cn-shanghai-internal.aliyuncs.com
            "domain"     => env("ALIYUN_OSS_DOMAIN", null),   // For example: oss.my-domain.com
            "use_ssl"    => env("ALIYUN_OSS_USE_SSL", false), // Whether to use https
            "prefix"     => env("ALIYUN_OSS_PREFIX", null),   // The prefix of the store path
        ],
        // ...
    ]
    ```

## Usage
```php
use Illuminate\Support\Facades\Storage;
$storage = Storage::disk("aliyun");
```
#### Write
```php
Storage::disk("aliyun")->putFile("dir/path", "/local/path/file.txt");
Storage::disk("aliyun")->putFileAs("dir/path", "/local/path/file.txt", "file.txt");

Storage::disk("aliyun")->put("dir/path/file.txt", file_get_contents("/local/path/file.txt"));
$fp = fopen("/local/path/file.txt","r");
Storage::disk("aliyun")->put("dir/path/file.txt", $fp);
fclose($fp);

Storage::disk("aliyun")->prepend("dir/path/file.txt", "Prepend Text"); 
Storage::disk("aliyun")->append("dir/path/file.txt", "Append Text");

Storage::disk("aliyun")->put("dir/path/secret.txt", "My secret", "private");
Storage::disk("aliyun")->put("dir/path/download.txt", "Download content", ["headers" => ["Content-Disposition" => "attachment;download.txt"]]);
```

#### Read
```php
Storage::disk("aliyun")->url("dir/path/file.txt");
Storage::disk("aliyun")->temporaryUrl("dir/path/file.txt");
Storage::disk("aliyun")->temporaryUrl("dir/path/file.txt", \Carbon\Carbon::now()->addMinutes(30));

Storage::disk("aliyun")->get("dir/path/file.txt"); 

Storage::disk("aliyun")->exists("dir/path/file.txt"); 
Storage::disk("aliyun")->size("dir/path/file.txt"); 
Storage::disk("aliyun")->lastModified("dir/path/file.txt");
```

#### Delete
```php
Storage::disk("aliyun")->delete("dir/path/file.txt");
Storage::disk("aliyun")->delete(["dir/path/file1.txt", "dir/path/file2.txt"]);
```

#### File operation
```php
Storage::disk("aliyun")->copy("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("aliyun")->move("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("aliyun")->rename("dir/path/file.txt", "dir/path/file_new.txt");
```

#### Folder operation
```php
Storage::disk("aliyun")->makeDirectory("dir/path"); 
Storage::disk("aliyun")->deleteDirectory("dir/path");

Storage::disk("aliyun")->files("dir/path");
Storage::disk("aliyun")->allFiles("dir/path");

Storage::disk("aliyun")->directories("dir/path"); 
Storage::disk("aliyun")->allDirectories("dir/path"); 
```

#### Use Plugin
```php
Storage::disk("aliyun")->appendObject("dir/path/news.txt", "The first line paragraph.", 0);
Storage::disk("aliyun")->appendObject("dir/path/news.txt", "The second line paragraph.", 25);
Storage::disk("aliyun")->appendObject("dir/path/news.txt", "The last line paragraph.", 51);

Storage::disk("aliyun")->appendFile("dir/path/file.zip", "dir/path/file.zip.001", 0);
Storage::disk("aliyun")->appendFile("dir/path/file.zip", "dir/path/file.zip.002", 1000);
Storage::disk("aliyun")->appendFile("dir/path/file.zip", "dir/path/file.zip.003", 1000);
```

#### Use OssClient
```php
$adapter  = Storage::disk("aliyun")->getAdapter();
$client = $adapter->getClient();
$bucketCors = $client->getBucketCors($adapter->getBucket());
```

## Documentation
- [Object storage OSS-aliyun](https://www.alibabacloud.com/help/en/object-storage-service)

## Issues
[Opening an Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## License
[MIT](LICENSE)

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_large)