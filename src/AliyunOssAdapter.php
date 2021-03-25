<?php

namespace AlphaSnow\AliyunOss;

use Carbon\Carbon;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\CanOverwriteFiles;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\Util;
use League\Flysystem\FileNotFoundException;
use OSS\Core\OssException;
use OSS\OssClient;
use Illuminate\Support\Facades\Log;

class AliyunOssAdapter extends AbstractAdapter implements CanOverwriteFiles
{
    use AliyunOssAdapterTrait;
    use AliyunOssReadTrait;

    /**
     * @var bool
     */
    protected $debug;
    /**
     * @var array
     */

    /**
     * @var OssClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $bucket;
    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var string
     */
    protected $cdnDomain;

    /**
     * @var bool
     */
    protected $ssl;

    /**
     * @var bool
     */
    protected $isCname;

    /**
     * @var array
     */
    protected $options = [

    ];

    /**
     * AliyunOssAdapter constructor.
     * @param OssClient $client
     * @param AliyunOssConfig $config
     * @param array $options
     */
    public function __construct(
        OssClient $client,
        AliyunOssConfig $config,
        array $options = []
    ) {
        $this->client = $client;
        $this->debug = $config->isDebug();
        $this->bucket = $config->getBucket();
        $this->endPoint = $config->getEndpoint();
        $this->ssl = $config->isSsl();
        $this->isCname = $config->isCname();
        $this->cdnDomain = $config->getCdnDomain();
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Used by \Illuminate\Filesystem\FilesystemAdapter::url
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        // if (!$this->has($path)) throw new FileNotFoundException($path.' not found');
        return ($this->ssl ? 'https://' : 'http://') . ($this->isCname ? ($this->cdnDomain == '' ? $this->endPoint : $this->cdnDomain) : $this->bucket . '.' . $this->endPoint) . '/' . ltrim($path, '/');
    }

    /**
     * Used by \Illuminate\Filesystem\FilesystemAdapter::temporaryUrl
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface|int $expiration
     * @param array $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getTemporaryUrl($path, $expiration, array $options = [])
    {
        if ($expiration instanceof Carbon) {
            return $this->client->generatePresignedUrl($this->bucket, $path, $expiration->getTimestamp(), $options);
        }
        return $this->client->signUrl($this->bucket, $path, $expiration, $options);
    }

    /**
     * 列举文件夹内文件列表；可递归获取子文件夹；
     * @param string $dirname 目录
     * @param bool $recursive 是否递归
     * @return mixed
     * @throws OssException
     */
    protected function listDirObjects($dirname = '', $recursive = false)
    {
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 1000;

        //存储结果
        $result = [];

        while (true) {
            $options = [
                'delimiter' => $delimiter,
                'prefix' => $dirname,
                'max-keys' => $maxkeys,
                'marker' => $nextMarker,
            ];

            try {
                $listObjectInfo = $this->client->listObjects($this->bucket, $options);
            } catch (OssException $e) {
                $this->logErr(__FUNCTION__, $e);
                // return false;
                throw $e;
            }

            $nextMarker = $listObjectInfo->getNextMarker(); // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
            $objectList = $listObjectInfo->getObjectList(); // 文件列表
            $prefixList = $listObjectInfo->getPrefixList(); // 目录列表

            if (!empty($objectList)) {
                foreach ($objectList as $objectInfo) {
                    $object = [];
                    $object['Prefix'] = $dirname;
                    $object['Key'] = $objectInfo->getKey();
                    $object['LastModified'] = $objectInfo->getLastModified();
                    $object['eTag'] = $objectInfo->getETag();
                    $object['Type'] = $objectInfo->getType();
                    $object['Size'] = $objectInfo->getSize();
                    $object['StorageClass'] = $objectInfo->getStorageClass();

                    $result['objects'][] = $object;
                }
            } else {
                $result["objects"] = [];
            }

            if (!empty($prefixList)) {
                foreach ($prefixList as $prefixInfo) {
                    $result['prefix'][] = $prefixInfo->getPrefix();
                }
            } else {
                $result['prefix'] = [];
            }

            //递归查询子目录所有文件
            if ($recursive) {
                foreach ($result['prefix'] as $pfix) {
                    $next = $this->listDirObjects($pfix, $recursive);
                    $result["objects"] = array_merge($result['objects'], $next["objects"]);
                }
            }

            //没有更多结果了
            if ($nextMarker === '') {
                break;
            }
        }

        return $result;
    }

    /**
     * @param string $path
     * @return array
     */
    protected function readObject($path)
    {
        $object = $this->applyPathPrefix($path);
        $result = [];
        $result['Body'] = $this->client->getObject($this->bucket, $object);
        $result = array_merge($result, ['type' => 'file']);
        return $this->normalizeResponse($result, $path);
    }

    /**
     * The the ACL visibility.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getObjectACL($path)
    {
        $metadata = $this->getVisibility($path);

        return $metadata['visibility'] === AdapterInterface::VISIBILITY_PUBLIC ? OssClient::OSS_ACL_TYPE_PUBLIC_READ : OssClient::OSS_ACL_TYPE_PRIVATE;
    }

    /**
     * Normalize a result from OSS.
     *
     * @param array $object
     * @param string $path
     *
     * @return array file metadata
     */
    protected function normalizeResponse(array $object, $path = null)
    {
        $result = ['path' => $path ?: $this->removePathPrefix(isset($object['Key']) ? $object['Key'] : $object['Prefix'])];
        $result['dirname'] = Util::dirname($result['path']);

        if (isset($object['LastModified'])) {
            $result['timestamp'] = strtotime($object['LastModified']);
        }

        if (substr($result['path'], -1) === '/') {
            $result['type'] = 'dir';
            $result['path'] = rtrim($result['path'], '/');

            return $result;
        }

        $result = array_merge($result, Util::map($object, AliyunOssUtil::$resultMap), ['type' => 'file']);

        return $result;
    }

    /**
     * Get options for a OSS call. done
     *
     * @param array $options
     *
     * @return array OSS options
     */
    protected function getOptions(array $options = [], Config $config = null)
    {
        $options = array_merge($this->options, $options);

        if ($config) {
            $options = array_merge($options, $this->getOptionsFromConfig($config));
        }

        return array(OssClient::OSS_HEADERS => $options);
    }

    /**
     * Retrieve options from a Config instance. done
     *
     * @param Config $config
     *
     * @return array
     */
    protected function getOptionsFromConfig(Config $config)
    {
        $options = AliyunOssUtil::getHeadersFromConfig($config);

        // 常用 visibility mimetype
        if ($visibility = $config->get('visibility')) {
            // Object ACL优先级高于Bucket ACL。
            $options[OssClient::OSS_OBJECT_ACL] = $visibility === AdapterInterface::VISIBILITY_PUBLIC ? OssClient::OSS_ACL_TYPE_PUBLIC_READ : OssClient::OSS_ACL_TYPE_PRIVATE;
        }
        if ($mimetype = $config->get('mimetype')) {
            $options[OssClient::OSS_CONTENT_TYPE] = $mimetype;
        }

        return $options;
    }

    /**
     * @return OssClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $func
     * @param \Exception $e
     */
    protected function logErr($func, $e)
    {
        if ($this->debug) {
            Log::error($func . ": FAILED");
            Log::error($e->getMessage());
        }
    }
}
