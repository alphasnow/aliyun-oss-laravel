<?php

namespace AlphaSnow\AliyunOss;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use League\Flysystem\Util;
use OSS\Core\OssException;
use OSS\OssClient;

trait AliyunOssAdapterTrait
{
    /**
     * {@inheritdoc}
     */
    public function write($path, $contents, Config $config)
    {
        $object = $this->applyPathPrefix($path);
        $options = $this->getOptions([], $config);

        if (!isset($options[OssClient::OSS_LENGTH])) {
            $options[OssClient::OSS_LENGTH] = Util::contentSize($contents);
        }
        if (!isset($options[OssClient::OSS_CONTENT_TYPE])) {
            $options[OssClient::OSS_CONTENT_TYPE] = Util::guessMimeType($path, $contents);
        }

        try {
            // https://help.aliyun.com/document_detail/31978.html
            $this->client->putObject($this->bucket, $object, $contents, $options);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return $this->normalizeResponse($options, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function writeStream($path, $resource, Config $config)
    {
        $object = $this->applyPathPrefix($path);
        $options = $this->getOptions([], $config);

        try {
            // https://help.aliyun.com/document_detail/31959.html
            $this->client->uploadStream($this->bucket, $object, $resource, $options);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return $this->normalizeResponse($options, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function writeFile($path, $filePath, Config $config)
    {
        $object = $this->applyPathPrefix($path);
        $options = $this->getOptions([], $config);

        if (!isset($options[OssClient::OSS_CONTENT_TYPE])) {
            $options[OssClient::OSS_CHECK_MD5] = true;
        }
        if (!isset($options[OssClient::OSS_CONTENT_TYPE])) {
            $options[OssClient::OSS_CONTENT_TYPE] = Util::guessMimeType($path, '');
        }

        try {
            $this->client->uploadFile($this->bucket, $object, $filePath, $options);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return $this->normalizeResponse($options, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function update($path, $contents, Config $config)
    {
        if (!$config->has('visibility') && !$config->has('ACL')) {
            // 新文件未配置权限情况下,继承旧文件权限
            $config->set(AliyunOssUtil::$metaMap['ACL'], $this->getObjectACL($path));
        }
        // 允许覆盖同名文件
        $config->set('x-oss-forbid-overwrite', 'false');

        return $this->write($path, $contents, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function updateStream($path, $resource, Config $config)
    {
        if (!$config->has('visibility') && !$config->has('ACL')) {
            // 新文件未配置权限情况下,继承旧文件权限
            $config->set(AliyunOssUtil::$metaMap['ACL'], $this->getObjectACL($path));
        }
        // 允许覆盖同名文件
        $config->set('x-oss-forbid-overwrite', 'false');

        return $this->writeStream($path, $resource, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function rename($path, $newpath)
    {
        if (!$this->copy($path, $newpath)) {
            return false;
        }

        return $this->delete($path);
    }

    /**
     * {@inheritdoc}
     */
    public function copy($path, $newpath)
    {
        $object = $this->applyPathPrefix($path);
        $newObject = $this->applyPathPrefix($newpath);

        try {
            $this->client->copyObject($this->bucket, $object, $this->bucket, $newObject);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        $bucket = $this->bucket;
        $object = $this->applyPathPrefix($path);

        try {
            $this->client->deleteObject($bucket, $object);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return !$this->has($path);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDir($dirname)
    {
        $dirname = rtrim($this->applyPathPrefix($dirname), '/') . '/';
        $dirObjects = $this->listDirObjects($dirname, true);

        if (count($dirObjects['objects']) > 0) {
            $objects = [];
            foreach ($dirObjects['objects'] as $object) {
                $objects[] = $object['Key'];
            }

            try {
                $this->client->deleteObjects($this->bucket, $objects);
            } catch (OssException $e) {
                $this->logErr(__FUNCTION__, $e);
                return false;
            }
        }

        try {
            $this->client->deleteObject($this->bucket, $dirname);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function createDir($dirname, Config $config)
    {
        $object = $this->applyPathPrefix($dirname);
        $options = $this->getOptionsFromConfig($config);

        try {
            $this->client->createObjectDir($this->bucket, $object, $options);
        } catch (OssException $e) {
            $this->logErr(__FUNCTION__, $e);
            return false;
        }

        return ['path' => $dirname, 'type' => 'dir'];
    }

    /**
     * {@inheritdoc}
     */
    public function setVisibility($path, $visibility)
    {
        $object = $this->applyPathPrefix($path);
        $acl = ($visibility === AdapterInterface::VISIBILITY_PUBLIC) ? OssClient::OSS_ACL_TYPE_PUBLIC_READ : OssClient::OSS_ACL_TYPE_PRIVATE;

        $this->client->putObjectAcl($this->bucket, $object, $acl);

        return compact('visibility');
    }
}
