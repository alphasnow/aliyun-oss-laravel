<?php

namespace AlphaSnow\AliyunOss;

use Aliyun\Flysystem\AliyunOss\AliyunOssAdapter as BaseAdapter;
use League\Flysystem\Adapter\CanOverwriteFiles;

/**
 * Class AliyunOssAdapter
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssAdapter extends BaseAdapter implements CanOverwriteFiles
{
}
