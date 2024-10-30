<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoIntegrationStatusIndicator
{

    /**
     * @param bool $active
     * @return string|null
     */
    public static function integrationFormMessage(bool $active)
    {
        if ($active) {
            return __('Integration is active', 'ipresso-integration-for-woocommerce');
        } else {
            return null;
        }
    }
}