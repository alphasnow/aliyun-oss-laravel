<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun;

use Illuminate\Support\Collection;

/**
 * Class Config
 */
class AliyunConfig extends Collection
{
    /**
     * @return string
     */
    public function getDomain()
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
    public function getRequestEndpoint()
    {
        if ($internal = $this->get('internal')) {
            return $internal;
        }
        if ($domain = $this->get('domain') && $this->get('reverse_proxy') == false) {
            return $domain;
        }
        return $this->get('endpoint');
    }

    /**
     * @param string $url
     * @return string
     */
    public function correctUrl(string $url): string
    {
        if ($this->get('internal')) {
            return str_replace($this->getInternalDomain(), $this->getDomain(), $url);
        }

        if ($this->get('domain')) {
            return str_replace($this->getEndpointDomain(), $this->getDomain(), $url);
        }

        return $url;
    }
}
