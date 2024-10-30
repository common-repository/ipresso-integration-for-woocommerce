<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoOrderResponse
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
     * @return WCiPressoOrderResponse
     */
    public function setMonitoringImage(string $monitoringImage): WCiPressoOrderResponse
    {
        $this->monitoringImage = $monitoringImage;
        return $this;
    }
}