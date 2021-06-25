English | [简体中文](README-CN.md)  

# Aliyun Oss Storage for Laravel
Alibaba Cloud Object Storage Service For Laravel

[![Build Status](https://github.com/alphasnow/aliyun-oss-laravel/workflows/CI/badge.svg)](https://github.com/alphasnow/aliyun-oss-laravel/actions)
[![Latest Stable Version](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/v/stable)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![Build Status](https://travis-ci.com/alphasnow/aliyun-oss-laravel.svg?branch=master)](https://travis-ci.com/alphasnow/aliyun-oss-laravel)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alphasnow/aliyun-oss-laravel/?branch=master)
[![License](https://poser.pugx.org/alphasnow/aliyun-oss-laravel/license)](https://packagist.org/packages/alphasnow/aliyun-oss-laravel)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_shield)

This package is a wrapper bridging [aliyun-oss-flysystem](https://github.com/alphasnow/aliyun-oss-flysystem) into Laravel as an available storage disk.

## Requirements
- PHP >= 7.0
- laravel/framework >= 5.5

## Installation
1. If you use the composer to manage project dependencies, run the following command in your project's root directory:
    ```bash
    $ composer require alphasnow/aliyun-oss-laravel
    ```
    You can also declare the dependency on Alibaba Cloud Object Storage Service For Laravel in the composer.json file.
    ```
    "require": {
        "alphasnow/aliyun-oss-laravel": "~2.8"
    }
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
    'default' => env('FILESYSTEM_DRIVER', 'aliyun'),
    // ...
    'disks'=>[
        // ...
        'aliyun' => [
            'driver'     => 'aliyun',
            'access_id'  => env('ALIYUN_OSS_ACCESS_ID'),      // For example: LTAI4**************qgcsA
            'access_key' => env('ALIYUN_OSS_ACCESS_KEY'),     // For example: PkT4F********************Bl9or
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

## Usage
```php
use Illuminate\Support\Facades\Storage;
$storage = Storage::disk('aliyun');
```
#### Write
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

Storage::disk('aliyun')->put('prefix/path/secret.txt', 'My secret', 'private');
Storage::disk('aliyun')->put('prefix/path/download.txt', 'Download content', ["headers" => ["Content-Disposition" => "attachment; filename=file.txt"]]);
```

#### Read
```php
Storage::disk('aliyun')->url('prefix/path/file.txt');
Storage::disk('aliyun')->temporaryUrl('prefix/path/file.txt');
Storage::disk('aliyun')->temporaryUrl('prefix/path/file.txt', \Carbon\Carbon::now()->addMinutes(30));

Storage::disk('aliyun')->get('prefix/path/file.txt'); 

Storage::disk('aliyun')->exists('prefix/path/file.txt'); 
Storage::disk('aliyun')->size('prefix/path/file.txt'); 
Storage::disk('aliyun')->lastModified('prefix/path/file.txt');
```

#### Delete
```php
Storage::disk('aliyun')->delete('prefix/path/file.txt');
Storage::disk('aliyun')->delete(['prefix/path/file1.txt', 'prefix/path/file2.txt']);
```

#### File operation
```php
Storage::disk('aliyun')->copy('prefix/path/file.txt', 'prefix/path/file_new.txt');
Storage::disk('aliyun')->move('prefix/path/file.txt', 'prefix/path/file_new.txt');
Storage::disk('aliyun')->rename('prefix/path/file.txt', 'prefix/path/file_new.txt');
```

#### Folder operation
```php
Storage::disk('aliyun')->makeDirectory('prefix/path'); 
Storage::disk('aliyun')->deleteDirectory('prefix/path');

Storage::disk('aliyun')->files('prefix/path');
Storage::disk('aliyun')->allFiles('prefix/path');

Storage::disk('aliyun')->directories('prefix/path'); 
Storage::disk('aliyun')->allDirectories('prefix/path'); 
```

## Documentation
- [Object storage OSS-aliyun](https://help.aliyun.com/product/31815.html)

## Issues
[Opening an Issue](https://github.com/alphasnow/aliyun-oss-laravel/issues/new)

## License
[MIT](LICENSE)

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Falphasnow%2Faliyun-oss-laravel?ref=badge_large)