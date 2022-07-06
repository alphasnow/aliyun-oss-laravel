<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\Collection;

/**
 * Class Config
 */
class Config extends Collection
{
    /**
     * @return string
     */
    public function getUrlDomain()
    {
        if ($this->get("domain")) {
            if (strpos("$".$this->get("domain"), "http") != false) {
                return $this->get("domain");
            }
            return $this->getProtocol()."://".$this->get("domain");
        }
        return $this->getEndpointDomain();
    }

    /**
     * @return string
     */
    protected function getEndpointDomain()
    {
        return $this->getProtocol()."://".$this->get("bucket").".".$this->get("endpoint");
    }

    /**
     * @return string
     */
    protected function getInternalDomain()
    {
        return $this->getProtocol()."://".$this->get("bucket").".".$this->get("internal");
    }

    /**
     * @return string
     */
    protected function getProtocol()
    {
        return $this->get("use_ssl", false) ? "https" : "http";
    }

    /**
     * @return string
     */
    public function getOssEndpoint()
    {
        if ($internal = $this->get("internal")) {
            return $internal;
        }
        if ($this->isCName()) {
            return $this->get("domain");
        }
        return $this->get("endpoint");
    }

    /**
     * @return bool
     */
    public function isCName()
    {
        return $this->get("use_domain_endpoint", false) && $this->get("domain");
    }

    /**
     * @deprecated
     * @return array
     */
    public function getOssClientParameters()
    {
        return [
            "accessKeyId" => $this->get("access_id"),
            "accessKeySecret" => $this->get("access_key"),
            "endpoint" => $this->getOssEndpoint(),
            "isCName" => $this->isCName(),
            "securityToken" => $this->get("security_token", null),
            "requestProxy" => $this->get("request_proxy", null)
        ];
    }

    /**
     * @param string $url
     * @return string
     */
    public function correctUrl($url)
    {
        if ($this->get("internal")) {
            return str_replace($this->getInternalDomain(), $this->getUrlDomain(), $url);
        }

        if ($this->get("domain") && $this->get("use_domain_endpoint") == false) {
            return str_replace($this->getEndpointDomain(), $this->getUrlDomain(), $url);
        }

        return $url;
    }
}
