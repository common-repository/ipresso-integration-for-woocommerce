<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoAuthorizationRequest implements JsonSerializable
{
    /**
     * @var string
     */
    private $woocommerceVersion;
    /**
     * @var string
     */
    private $moduleVersion;
    /**
     * @var string
     */
    private $domain;

    /**
     * @return string
     */
    public function getWoocommerceVersion(): string
    {
        return $this->woocommerceVersion;
    }

    /**
     * @param string $woocommerceVersion
     * @return WCiPressoAuthorizationRequest
     */
    public function setWoocommerceVersion(string $woocommerceVersion): WCiPressoAuthorizationRequest
    {
        $this->woocommerceVersion = $woocommerceVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getModuleVersion(): string
    {
        return $this->moduleVersion;
    }

    /**
     * @param string $moduleVersion
     * @return WCiPressoAuthorizationRequest
     */
    public function setModuleVersion(string $moduleVersion): WCiPressoAuthorizationRequest
    {
        $this->moduleVersion = $moduleVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return WCiPressoAuthorizationRequest
     */
    public function setDomain(string $domain): WCiPressoAuthorizationRequest
    {
        $this->domain = $domain;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}