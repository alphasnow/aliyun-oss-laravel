<?php

namespace AlphaSnow\AliyunOss;

use Illuminate\Support\Collection;

class AliyunOssConfig extends Collection
{
    /**
     * @return string
     */
    public function getUrlDomain()
    {
        $protocol = $this->get('use_ssl', false) ? 'https' : 'http';
        $domain = $this->get('domain') ?: $this->get('bucket').'.'.$this->get('endpoint');
        return $protocol.'://'.$domain;
    }

    public function getInternalDomain()
    {
        $protocol = $this->get('use_ssl', false) ? 'https' : 'http';
        return $protocol.'://'.$this->get('bucket').'.'.$this->get('internal');
    }

    /**
     * @return string
     */
    public function getOssEndpoint()
    {
        if ($this->isCName()) {
            return $this->get('domain');
        }
        if ($internal = $this->get('internal')) {
            return $internal;
        }
        return $this->get('endpoint');
    }

    public function isCName()
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
}
