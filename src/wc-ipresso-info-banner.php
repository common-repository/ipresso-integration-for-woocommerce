<?php

class WCiPressoInfoBanner
{
    /** @var WCiPressoInfoBanner Class Instance */
    private static $_instance;

    private static $NOTICE_NAME = 'dismiss-ipresso-info-banner';
    private static $NOTICE_REFERER = 'woocommerce_ipresso_info_banner_dismiss';

    /**
     * @var bool
     */
    private $isDismissed;

    public function __construct($dismissed = false, $apiKey = '')
    {
        $this->isDismissed = (bool)$dismissed;
        if (!empty($apiKey)) {
            $this->isDismissed = true;
        }

        if (true === $this->isDismissed) {
            return;
        }

        add_action('admin_notices', [$this, 'bannerToShow']);
        add_action('admin_init', [$this, 'dismissBanner']);
    }

    /**
     * @param bool $dismissedInfoBanner
     * @param $apiKey
     * @return WCiPressoInfoBanner
     */
    public static function getInstance(bool $dismissedInfoBanner = false, $apiKey = null): WCiPressoInfoBanner
    {
        return null === self::$_instance
            ? (self::$_instance = new self($dismissedInfoBanner, $apiKey)) : self::$_instance;
    }

    public function bannerToShow()
    {
        $screen = get_current_screen();

        if (!in_array($screen->base, ['woocommerce_page_wc-settings', 'plugins']) ||
            $screen->is_network ||
            $screen->action
        ) {
            return;
        }

        $this->showBanner();
    }

    private function showBanner()
    {
        $configurationUrl = sanitize_url($this->getConfigurationUrl());
        $dismissBannerUrl = sanitize_url($this->getDismissBannerUrl());

        $header = __('iPresso integration with WooCommerce', 'ipresso-integration-for-woocommerce');
        $message = sprintf(
            __(
                '<a href="%s">Complete the setup</a> to enable iPresso integration with Woocommerce.',
                'ipresso-integration-for-woocommerce'
            ),
            $configurationUrl
        );

        $message = wp_kses(
            $message,
            [
                'a' => [
                    'href' => [],
                    'title' => [],
                ]
            ]
        );

        $banerElementContent =
            '<div class="updated fade"><p><strong>' . $header . '</strong> ' .
            '<a href="' . sanitize_url($dismissBannerUrl) . '" title="' .
            __('Dismiss this notice.', 'ipresso-integration-for-woocommerce') . '"> ' .
            __('(Dismiss)', 'ipresso-integration-for-woocommerce') . '</a>' .
            '<p>' . $message . "</p></div>\n";

        echo(
            wp_kses(
                $banerElementContent,
                [
                    'div' => [
                        'class' => []
                    ],
                    'p' => [
                    ],
                    'a' => [
                      'href' => [],
                      'title' => [],
                    ],
                    'strong' => [],
                ]
            )
        );
    }

    private function getConfigurationUrl()
    {
        return esc_url(admin_url('admin.php?page=wc-settings&tab=integration&section=ipresso'));
    }

    private function getDismissBannerUrl(): string
    {
        $dismissUrl = admin_url('admin.php');

        $dismissUrl = add_query_arg([
            'page' => 'wc-settings',
            'tab' => 'integration',
            'wc-notice' => self::$NOTICE_NAME
        ], $dismissUrl);

        return esc_url(wp_nonce_url($dismissUrl, self::$NOTICE_REFERER));
    }

    public function dismissBanner()
    {
        if (
            !isset($_GET['wc-notice']) ||
            self::$NOTICE_NAME !== $_GET['wc-notice'] ||
            !check_admin_referer(self::$NOTICE_REFERER)
        ) {
            return;
        }

        update_option(WCiPressoIntegration::$OPTION_INFO_BANNER, true);

        if (wp_get_referer()) {
            wp_safe_redirect(wp_get_referer());
        } else {
            wp_safe_redirect(admin_url('admin.php?page=wc-settings&tab=integration'));
        }
    }
}