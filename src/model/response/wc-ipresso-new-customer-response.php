<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoNewCustomerResponse
{
    /**
     * @var string
     */
    private $monitoringImage;

    /**
     * @return string
     */
    public function getMonitoringImage(): string
    {
        return $this->monitoringImage;
    }

    /**
     * @param string $monitoringImage
     * @return WCiPressoNewCustomerResponse
     */
    public function setMonitoringImage(string $monitoringImage): WCiPressoNewCustomerResponse
    {
        $this->monitoringImage = $monitoringImage;
        return $this;
    }
}