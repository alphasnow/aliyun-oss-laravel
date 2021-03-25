<?php

namespace AlphaSnow\AliyunOss;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Util;
use OSS\Core\OssException;
use OSS\OssClient;

trait AliyunOssReadTrait
{
    /**
     * {@inheritdoc}
     */
    public function has($path)
    {
        $object = $this->applyPathPrefix($path);

        return $this->client->doesObjectExist($this->bucket, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function read($path)
    {
        $result = $this->readObject($path);
        $result['contents'] = (string)$result['raw_contents'];
        unset($result['raw_contents']);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function readStream($path)
    {
        $result = $this->readObject($path);
        $result['stream'] = $result['raw_contents'];
        rewind($result['stream']);
        // Ensure the EntityBody object destruction doesn't close the stream
        // $result['raw_contents']->detachStream();
        unset($result['raw_contents']);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function listContents($directory = '', $recursive = false)
    {
        $dirObjects = $this->listDirObjects($directory, true);
        $contents = $dirObjects["objects"];

        $result = array_map([$this, 'normalizeResponse'], $contents);
        $result = array_filter($result, function ($value) {
            return $value['path'] !== false;
        });

        return Util::emulateDirectories($result);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($path)
    {
        $object = $this->applyPathPrefix($path);

        try {
            $objectMeta = $this->client->getObjectMeta($this->bucket, $object);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return $objectMeta;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize($path)
    {
        $object = $this->getMetadata($path);
        $object['size'] = $object['content-length'];
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimetype($path)
    {
        if ($object = $this->getMetadata($path)) {
            $object['mimetype'] = $object['content-type'];
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp($path)
    {
        if ($object = $this->getMetadata($path)) {
            $object['timestamp'] = strtotime($object['last-modified']);
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibility($path)
    {
        $object = $this->applyPathPrefix($path);
        try {
            $acl = $this->client->getObjectAcl($this->bucket, $object);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        if ($acl == OssClient::OSS_ACL_TYPE_PUBLIC_READ) {
            $res['visibility'] = AdapterInterface::VISIBILITY_PUBLIC;
        } else {
            $res['visibility'] = AdapterInterface::VISIBILITY_PRIVATE;
        }

        return $res;
    }
}
