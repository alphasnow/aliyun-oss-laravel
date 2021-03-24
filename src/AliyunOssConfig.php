<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Contracts\Support\Arrayable;
use LogicException;

/**
 * Class AliyunOssConfig
 * @package AlphaSnow\AliyunOss
 */
class AliyunOssConfig implements Arrayable
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

    public function toArray()
    {
        return get_object_vars($this);
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
     * @param string $driver
     * @return AliyunOssConfig
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessId()
    {
        return $this->accessId;
    }

    /**
     * @param string $accessId
     * @return AliyunOssConfig
     */
    public function setAccessId($accessId)
    {
        $this->accessId = $accessId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * @param string $accessKey
     * @return AliyunOssConfig
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     * @return AliyunOssConfig
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     * @return AliyunOssConfig
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndpointInternal()
    {
        return $this->endpointInternal;
    }

    /**
     * @param string $endpointInternal
     * @return AliyunOssConfig
     */
    public function setEndpointInternal($endpointInternal)
    {
        $this->endpointInternal = $endpointInternal;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSsl()
    {
        return $this->ssl;
    }

    /**
     * @param bool $ssl
     * @return AliyunOssConfig
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCname()
    {
        return $this->isCname;
    }

    /**
     * @param bool $isCname
     * @return AliyunOssConfig
     */
    public function setIsCname($isCname)
    {
        $this->isCname = $isCname;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return AliyunOssConfig
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return string
     */
    public function getCdnDomain()
    {
        return $this->cdnDomain;
    }

    /**
     * @param string $cdnDomain
     * @return AliyunOssConfig
     */
    public function setCdnDomain($cdnDomain)
    {
        $this->cdnDomain = $cdnDomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityToken()
    {
        return $this->securityToken;
    }

    /**
     * @param string $securityToken
     * @return AliyunOssConfig
     */
    public function setSecurityToken($securityToken)
    {
        $this->securityToken = $securityToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestProxy()
    {
        return $this->requestProxy;
    }

    /**
     * @param string $requestProxy
     * @return AliyunOssConfig
     */
    public function setRequestProxy($requestProxy)
    {
        $this->requestProxy = $requestProxy;
        return $this;
    }
}
