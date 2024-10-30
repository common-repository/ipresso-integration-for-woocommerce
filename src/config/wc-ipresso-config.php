<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class WCiPressoConfig
{
    /**
     * @var string
     */
    public static $REST_API_ENDPOINT = 'https://iesb.ipresso.pl/';
    /**
     * @var string
     */
    public static $VERSION = '1.3.0';

    public static $INTEGRATION_NAME = 'Woocommerce';

    public static function getWordpressVersion(): string
    {
        global $wp_version;
        return $wp_version;
    }

    public static function getWoocommerceDomain(): string
    {
        return get_rest_url();
    }
}
