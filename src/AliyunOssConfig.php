<?php

namespace AlphaSnow\AliyunOss;

use LogicException;

/**
 * Class AliyunOssConfig
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssConfig
{
    /**
     * @var string
     */
    protected $driver = 'aliyun';
    /**
     * @var string
     */
    protected $accessId;
    /**
     * @var string
     */
    protected $accessKey;
    /**
     * @var string
     */
    protected $bucket;
    /**
     * @var string
     */
    protected $endpoint = 'oss-cn-shanghai.aliyuncs.com';
    /**
     * @var string
     */
    protected $endpointInternal = '';
    /**
     * @var bool
     */
    protected $ssl = false;
    /**
     * @var bool
     */
    protected $isCname = false;
    /**
     * @var bool
     */
    protected $debug = false;
    /**
     * @var string
     */
    protected $cdnDomain = '';

    /**
     * @var string
     */
    protected $securityToken;

    /**
     * @var string
     */
    protected $requestProxy;

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function checkRequired()
    {
        if (!$this->accessId) {
            throw new LogicException('Empty accessId');
        }
        if (!$this->accessKey) {
            throw new LogicException('Empty accessKey');
        }
        if (!$this->bucket) {
            throw new LogicException('Empty bucket');
        }
    }

    public function getOssEndpoint()
    {
        if ($this->isCname) {
            if (empty($this->cdnDomain)) {
                throw new LogicException('Empty cdnDomain');
            }
            return $this->cdnDomain;
        }

        if (!empty($this->endpointInternal)) {
            return $this->endpointInternal;
        }

        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getAccessId()
    {
        return $this->accessId;
    }

    /**
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getEndpointInternal()
    {
        return $this->endpointInternal;
    }

    /**
     * @return bool
     */
    public function isSsl()
    {
        return $this->ssl;
    }

    /**
     * @return bool
     */
    public function isCname()
    {
        return $this->isCname;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @return string
     */
    public function getCdnDomain()
    {
        return $this->cdnDomain;
    }

    /**
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @return string
     */
    public function getRequestProxy()
    {
        return $this->requestProxy;
    }
}
