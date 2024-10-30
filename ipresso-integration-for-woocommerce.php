<?php
/**
 * Plugin Name: iPresso integration for WooCommerce
 * Plugin URI: https://www.ipresso.com/features/integrations
 * Description: The plugin allows you to integrate your Woocommerce store with iPresso. It saves activities about added products to cart, and placed orders.
 * Version: 1.3
 * Author: iPresso SA
 * Author URI: https://ipresso.com
 * Text Domain: ipresso-integration-for-woocommerce
 * Domain Path: languages/
 * Requires at least: 4.4.0
 * Tested up to: 6.2.0
 **/

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('is_plugin_inactive')) {
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}


if (!class_exists('WoocommerceiPresso')) :
    final class WoocommerceiPresso
    {
        protected static $_instance = null;

        protected static $wcMinimalVersion = '6.0.0';

        public function __construct()
        {
            add_action('init', array($this, 'loadTextdomain'));

            if (
                class_exists('WC_Integration') &&
                defined('WOOCOMMERCE_VERSION') &&
                version_compare(WOOCOMMERCE_VERSION, self::$wcMinimalVersion, '>=')
            ) {
                include_once 'src/config/wc-ipresso-config.php';
                include_once 'src/wc-ipresso-integration.php';
                add_filter('woocommerce_integrations', array($this, 'enableIntegration'));

            } else {
                add_action('admin_notices', array($this, 'noticeMissingWoocommerce'));
            }
        }

        public function enableIntegration($integrations)
        {
            $integrations[] = 'WCiPressoIntegration';

            return $integrations;
        }

        public function clearData()
        {

        }

        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function loadTextdomain()
        {
            $locale = apply_filters('plugin_locale', get_locale(), 'woocommerce-ipresso');

            load_textdomain(
                'woocommerce-ipresso',
                trailingslashit(
                    WP_LANG_DIR
                ) . 'ipresso-integration-for-woocommerce/ipresso-integration-for-woocommerce-' . $locale . '.mo'
            );
            load_plugin_textdomain('woocommerce-ipresso', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public function noticeMissingWoocommerce()
        {
            echo '
                <div class="error">
                    <p>' . sprintf(
                    __(
                        'WooCommerce iPresso integration plugin depends on the last version of %s!',
                        'woocommerce-ipresso'
                    ),
                    '<a href="https://woocommerce.com/" target="_blank">' . __(
                        'WooCommerce',
                        'woocommerce-ipresso'
                    ) . '</a>'
                ) . '
                    </p>
                </div>
                ';
        }
    }

    add_action('plugins_loaded', array('WoocommerceiPresso', 'instance'), 0);
endif;

