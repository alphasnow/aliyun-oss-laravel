English | [简体中文](README-CN.md)  

# Aliyun Oss Storage for Laravel
Alibaba Cloud Object Storage Service For Laravel

[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Total Downloads](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/downloads)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Test](https://github.com/alphasnow/aliyun-oss-laravel/workflows/Test/badge.svg?branch=4.x)](https://github.com/alphasnow/aliyun-oss-laravel/actions?query=branch:4.x)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Coverage Status](https://coveralls.io/repos/github/alphasnow/aliyun-oss-laravel/badge.svg?branch=4)](https://coveralls.io/github/alphasnow/aliyun-oss-laravel?branch=4)

This package is a wrapper bridging [aliyun-oss-flysystem](https://github.com/alphasnow/aliyun-oss-flysystem) into Laravel as an available storage disk.  
If client direct transmission is required, Use web server signature direct transmission OSS extension package [aliyun-oss-appserver](https://github.com/alphasnow/aliyun-oss-appserver).  

## Compatibility

| laravel      | aliyun-oss-laravel | driver | readme |
|:-------------|:-------------------|:-------|:-------|
| \>=5.5,\<9.0 | ^3.0               | aliyun | [readme](https://github.com/alphasnow/aliyun-oss-laravel/blob/3.x/README.md) |
| \>=9.0       | ^4.0               | oss    | [readme](https://github.com/alphasnow/aliyun-oss-laravel/blob/4.x/README.md) |

## Installation
1. If you use the composer to manage project dependencies, run the following command in your project"s root directory:
    ```bash
    composer require alphasnow/aliyun-oss-laravel
    ```
    Then run `composer install` to install the dependency.

2. Modify the environment file `.env`
    ```env
    OSS_ACCESS_KEY_ID=<Your aliyun accessKeyId, Required>
    OSS_ACCESS_KEY_SECRET=<Your aliyun accessKeySecret, Required>
    OSS_BUCKET=<Your oss bucket name, Required>
    OSS_ENDPOINT=<Your oss endpoint domain, Required>
    ```

3. (Optional) Modify the configuration file `config/filesystems.php`
    ```php
    "default" => env("FILESYSTEM_DRIVER", "oss"),
    // ...
    "disks"=>[
        // ...
        "oss" => [
            "driver"            => "oss",
            "access_key_id"     => env("OSS_ACCESS_KEY_ID"),           // Required, YourAccessKeyId
            "access_key_secret" => env("OSS_ACCESS_KEY_SECRET"),       // Required, YourAccessKeySecret
            "bucket"            => env("OSS_BUCKET"),                  // Required, For example: my-bucket
            "endpoint"          => env("OSS_ENDPOINT"),                // Required, For example: oss-cn-shanghai.aliyuncs.com
            "internal"          => env("OSS_INTERNAL", null),          // Optional, For example: oss-cn-shanghai-internal.aliyuncs.com
            "domain"            => env("OSS_DOMAIN", null),            // Optional, For example: oss.my-domain.com
            "prefix"            => env("OSS_PREFIX", ""),              // Optional, The prefix of the store path
            "use_ssl"           => env("OSS_SSL", false),              // Optional, Whether to use HTTPS
            "reverse_proxy"     => env("OSS_REVERSE_PROXY", false),    // Optional, Whether to use the Reverse proxy, such as nginx
            "options"           => [],                                 // Optional, Add global configuration parameters, For example: [\OSS\OssClient::OSS_CHECK_MD5 => false]
            "macros"            => []                                  // Optional, Add custom Macro, For example: [\App\Macros\ListBuckets::class, \App\Macros\CreateBucket::class]
        ],
        // ...
    ]
    ```

## Usage
```php
use Illuminate\Support\Facades\Storage;
$storage = Storage::disk("oss");
```
#### Write
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
Storage::disk("oss")->put("dir/path/download.txt", "Download content", ["headers" => ["Content-Disposition" => "attachment;filename=download.txt"]]);
```

#### Read
```php
Storage::disk("oss")->url("dir/path/file.txt");
Storage::disk("oss")->temporaryUrl("dir/path/file.txt", \Carbon\Carbon::now()->addMinutes(30));

Storage::disk("oss")->get("dir/path/file.txt"); 

Storage::disk("oss")->exists("dir/path/file.txt"); 
Storage::disk("oss")->size("dir/path/file.txt"); 
Storage::disk("oss")->lastModified("dir/path/file.txt");
```

#### Delete
```php
Storage::disk("oss")->delete("dir/path/file.txt");
Storage::disk("oss")->delete(["dir/path/file1.txt", "dir/path/file2.txt"]);
```

#### File operation
```php
Storage::disk("oss")->copy("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("oss")->move("dir/path/file.txt", "dir/path/file_new.txt");
Storage::disk("oss")->rename("dir/path/file.txt", "dir/path/file_new.txt");
```

#### Folder operation
```php
Storage::disk("oss")->makeDirectory("dir/path"); 
Storage::disk("oss")->deleteDirectory("dir/path");

Storage::disk("oss")->files("dir/path");
Storage::disk("oss")->allFiles("dir/path");

Storage::disk("oss")->directories("dir/path"); 
Storage::disk("oss")->allDirectories("dir/path"); 
```

#### Use Macro
```php
Storage::disk("oss")->appendObject("dir/path/news.txt", "The first line paragraph.", 0);
Storage::disk("oss")->appendObject("dir/path/news.txt", "The second line paragraph.", 25);
Storage::disk("oss")->appendObject("dir/path/news.txt", "The last line paragraph.", 51);

Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.001", 0);
Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.002", 1024);
Storage::disk("oss")->appendFile("dir/path/file.zip", "dir/path/file.zip.003", 1024);
```

##### Add Custom Macro
1. Add Macro
    ```php
    namespace App\Macros;
    use AlphaSnow\LaravelFilesystem\Aliyun\Macros\AliyunMacro;
    
    class ListBuckets implements AliyunMacro
    {
        // ... 
    }
    ```
   Reference code: [AppendObject.php](https://github.com/alphasnow/aliyun-oss-laravel/blob/4.5.0/src/Macros/AppendObject.php)

2. Modify the config
    ```php
    [
        "macros" => [\App\Macros\ListBuckets::class]
    ]
    ```

3. Use Macro
    ```php
    Storage::disk("oss")->listBuckets()
    ```

#### Use OssClient
```php
use AlphaSnow\LaravelFilesystem\Aliyun\OssClientAdapter;

$adapter = new OssClientAdapter(Storage::disk("oss"));
$adapter->client()->appendObject($adapter->bucket(), $adapter->path("dir/path/file.txt"), "contents", 0, $adapter->options(["visibility" => "private"]));
```

## Documentation
- [Object storage OSS-aliyun](https://www.alibabacloud.com/help/en/object-storage-service)

## Issues
[Opening an Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## License
[MIT](LICENSE)