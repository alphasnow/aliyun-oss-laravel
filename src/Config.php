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
        if ($this->get('domain')) {
            return $this->getProtocol().'://'.$this->get('domain');
        }
        return $this->getEndpointDomain();
    }

    /**
     * @return string
     */
    protected function getEndpointDomain()
    {
        return $this->getProtocol().'://'.$this->get('bucket').'.'.$this->get('endpoint');
    }
    /**
     * @return string
     */
    protected function getInternalDomain()
    {
        return $this->getProtocol().'://'.$this->get('bucket').'.'.$this->get('internal');
    }

    /**
     * @return string
     */
    protected function getProtocol()
    {
        return $this->get('use_ssl', false) ? 'https' : 'http';
    }

    /**
     * @return string
     */
    public function getOssEndpoint()
    {
        if ($internal = $this->get('internal')) {
            return $internal;
        }
        if ($this->isCName()) {
            return $this->get('domain');
        }
        return $this->get('endpoint');
    }

    /**
     * @return bool
     */
    protected function isCName()
    {
        return $this->get('use_domain_endpoint') && $this->get('domain');
    }

    /**
     * @return array
     */
    public function getOssClientParameters()
    {
        return [
            'accessKeyId' => $this->get('access_id'),
            'accessKeySecret' => $this->get('access_key'),
            'endpoint' => $this->getOssEndpoint(),
            'isCName' => $this->isCName(),
            'securityToken' => $this->get('security_token', null)
        ];
    }

    /**
     * @param string $url
     * @return string
     */
    public function correctUrl($url)
    {
        // correct internal domain
        if ($this->get('internal')) {
            return str_replace($this->getInternalDomain(), $this->getUrlDomain(), $url);
        }

        // correct domain
        if ($this->get('domain') && $this->get('use_domain_endpoint') == false) {
            return str_replace($this->getEndpointDomain(), $this->getUrlDomain(), $url);
        }

        return $url;
    }
}
