<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoAuthorizationResponse
{
    /**
     * @var string
     */
    private $monitoringCode;

    /**
     * @var WCiPressoConsent
     */
    private $consent;

    /**
     * @return string
     */
    public function getMonitoringCode(): string
    {
        return $this->monitoringCode;
    }

    /**
     * @param string $monitoringCode
     * @return WCiPressoAuthorizationResponse
     */
    public function setMonitoringCode(string $monitoringCode): WCiPressoAuthorizationResponse
    {
        $this->monitoringCode = $monitoringCode;
        return $this;
    }

    /**
     * @return WCiPressoConsent
     */
    public function getConsent(): WCiPressoConsent
    {
        return $this->consent;
    }

    /**
     * @param WCiPressoConsent $consent
     * @return WCiPressoAuthorizationResponse
     */
    public function setConsent(WCiPressoConsent $consent): WCiPressoAuthorizationResponse
    {
        $this->consent = $consent;
        return $this;
    }
}