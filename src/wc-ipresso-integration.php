<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @extends WC_Integration
 */
class WCiPressoIntegration extends WC_Integration
{
    public static $OPTION_API_KEY = 'apiKey';
    public static $OPTION_API_KEY_FORM = 'apiKeyForm';
    public static $OPTION_MONITORING_CODE = 'monitoringCode';
    public static $OPTION_CONSENT_ID = 'consentId';
    public static $OPTION_CONSENT_AGREE = 'consentAgree';
    public static $OPTION_CONSENT_CONTENT = 'consentContent';

    public static $OPTION_INFO_BANNER = 'WCiPressoDismissedInfoBanner';
    public static $CONSENT_FIELD_ID = 'ipresso_woocommerce_newsletter';
    public static $MONITORING_IMAGE_SESSION = 'ipressoWoocommerceMonitoringImage';

    public static $INTEGRATION_ID = 'ipresso';
    public static $INTEGRATION_NAME = 'iPresso';

    /** @var WCiPressoIntegration */
    private static $_instance;

    public function __construct()
    {
        $this->id = self::$INTEGRATION_ID;
        $this->method_title = __(self::$INTEGRATION_NAME, 'ipresso-integration-for-woocommerce');
        $this->method_description = __('Provide API key to start integration of your woocommerce store with iPresso', 'woocommerce-ipresso');
        $dismissedInfoBanner = get_option(self::$OPTION_INFO_BANNER);

        $this->prepareFormFields();
        $this->init_settings();
        $this->includeModule();

        WCiPressoJsFront::getInstance();
        WCiPressoRestApi::getInstance($this->get_option(self::$OPTION_API_KEY));

        if (is_admin()) {
            include_once 'wc-ipresso-info-banner.php';
            WCiPressoInfoBanner::getInstance($dismissedInfoBanner, $this->get_option(self::$OPTION_API_KEY, null));
        }

        $this->addActions();
    }

    public function prepareFormFields()
    {
        $this->form_fields = [
            self::$OPTION_API_KEY_FORM => [
                'title' => __('API key', 'ipresso-integration-for-woocommerce'),
                'description' => sprintf(
                    __(
                        'Log into your iPresso account to find your API key. <a href="%s">See here for more information</a>.',
                        'woocommerce-ipresso'
                    ),
                    'https://ipresso.com'
                ),
                'type' => 'text',
                'placeholder' => __('Provide your API key', 'ipresso-integration-for-woocommerce'),
            ]
        ];
    }

    private function includeModule()
    {
        include_once 'wc-ipresso-front-js.php';
        include_once 'wc-ipresso-rest-api.php';
        include_once 'helper/wc-ipresso-integration-status-indicator.php';
        include_once 'helper/wc-ipresso-js-api-builder.php';
        include_once 'helper/wc-ipresso-rest-api-builder.php';
        include_once 'model/request/wc-ipresso-add-to-basket-request.php';
        include_once 'model/request/wc-ipresso-authorization-request.php';
        include_once 'model/request/wc-ipresso-order-request.php';
        include_once 'model/request/wc-ipresso-new-customer-request.php';
        include_once 'model/request/partial/wc-ipresso-adress.php';
        include_once 'model/request/partial/wc-ipresso-customer.php';
        include_once 'model/request/partial/wc-ipresso-simple-customer.php';
        include_once 'model/request/partial/wc-ipresso-product.php';
        include_once 'model/response/wc-ipresso-authorization-response.php';
        include_once 'model/response/wc-ipresso-order-response.php';
        include_once 'model/response/wc-ipresso-new-customer-response.php';
        include_once 'model/response/partial/wc-ipresso-consent.php';
    }

    private function addActions()
    {
        add_action('wp_head', [$this, 'displayMonitoringCode'], 90);
        add_filter('woocommerce_tracker_data', [$this, 'track_options']);
        add_action('woocommerce_update_options_integration_' . $this->id, [$this, 'processAdminOptions']);

        add_action('woocommerce_after_add_to_cart_button', [$this, 'addToBasket']);
        add_action('woocommerce_after_shop_loop_item', [$this, 'addToBasketListing']);

        add_action('woocommerce_register_form', [$this, 'addConsentCheckout']);
        add_action('woocommerce_created_customer', [$this, 'saveCustomer']);
        add_action('woocommerce_save_account_details', [$this, 'saveCustomer']);
        add_action('woocommerce_customer_save_address', [$this, 'saveCustomer']);
        add_action('woocommerce_account_content', [$this, 'showMonitoringImage']);
        add_action('woocommerce_after_order_details', [$this, 'showMonitoringImage']);
        add_action('woocommerce_after_shop_loop', [$this, 'showMonitoringImage']);

        add_action('woocommerce_checkout_before_terms_and_conditions', [$this, 'addConsentCheckout']);
        add_action('woocommerce_checkout_order_processed', [$this, 'purchaseOrder']);
        add_action('woocommerce_thankyou', [$this, 'showMonitoringImage']);

        add_action('rest_api_init', function () {
            require_once __DIR__ . '/wc-ipresso-webhook-api.php';
            require_once __DIR__ . '/wc-ipresso-order-api.php';
            $controller = new WCiPressoWebhookApi();
            $controller->register_routes();
            $orderApi = new WCiPressoOrderApi();
            $orderApi->registerRoutes();
        });
    }

    /**
     * @return WCiPressoIntegration
     */
    public static function getInstance(): WCiPressoIntegration
    {
        return null === self::$_instance ? (self::$_instance = new self()) : self::$_instance;
    }

    /**
     * @param string $apiKey
     * @return bool
     */
    public function checkApiKey(string $apiKey): bool
    {
        if ($storedApiKey = $this->get_option(self::$OPTION_API_KEY)) {
            return ($apiKey === $storedApiKey);
        }

        return false;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * @return bool
     */
    public function deleteIntegration(): bool
    {
        $this->settings[self::$OPTION_MONITORING_CODE] = null;
        $this->settings[self::$OPTION_API_KEY] = null;
        $this->settings[self::$OPTION_API_KEY_FORM] = null;
        $this->settings[self::$OPTION_CONSENT_ID] = null;
        $this->settings[self::$OPTION_CONSENT_CONTENT] = null;

        update_option(WCiPressoIntegration::$OPTION_INFO_BANNER, false);

        return $this->saveConfig();
    }

    /**
     * @param int $consentId
     * @param string $consentContent
     * @return bool
     */
    public function changeConsent(int $consentId, string $consentContent): bool
    {
        if ($this->settings[self::$OPTION_CONSENT_ID] === $consentId && $this->settings[self::$OPTION_CONSENT_CONTENT] === $consentContent) {
            return true;
        }
        $this->settings[self::$OPTION_CONSENT_ID] = $consentId;
        $this->settings[self::$OPTION_CONSENT_CONTENT] = $consentContent;

        return $this->saveConfig();
    }

    /**
     * @return bool
     */
    public function processAdminOptions(): bool
    {
        $postData = $this->get_post_data();
        $integrationApiKey = sanitize_text_field($postData['woocommerce_' . $this->id . '_' . self::$OPTION_API_KEY_FORM]);

        if (empty($integrationApiKey)) {
            $this->deleteIntegration();
            return true;
        }

        $authorization = new WCiPressoAuthorizationRequest();
        $authorization->setDomain(WCiPressoConfig::getWoocommerceDomain())
            ->setWoocommerceVersion(WCiPressoConfig::getWordpressVersion())
            ->setModuleVersion(WCiPressoConfig::$VERSION);

        try {
            $authorizationResponse = WCiPressoRestApi::getInstance($integrationApiKey)->sendAuthorize($authorization);
        } catch (Exception $exception) {
            $this->add_error($exception->getMessage());
            return false;
        }

        $this->settings[self::$OPTION_MONITORING_CODE] = $authorizationResponse->getMonitoringCode();
        $this->settings[self::$OPTION_API_KEY] = $integrationApiKey;
        $this->settings[self::$OPTION_API_KEY_FORM] = WCiPressoIntegrationStatusIndicator::integrationFormMessage(true);
        $this->settings[self::$OPTION_CONSENT_ID] = $authorizationResponse->getConsent()->getId();
        $this->settings[self::$OPTION_CONSENT_CONTENT] = $authorizationResponse->getConsent()->getContent();

        return $this->saveConfig();
    }

    /**
     * @return bool
     */
    public function displayMonitoringCode(): bool
    {
        $monitoringCode = $this->settings[self::$OPTION_MONITORING_CODE];
        if ($monitoringCode) {
            WCiPressoJsFront::getInstance()->injectMonitoringCode($monitoringCode);
            return true;
        } else {
            return false;
        }
    }

    /**
     * action_type: add_to_cart
     */
    public function addToBasket()
    {
        global $product;
        if ($product instanceof WC_Product) {
            WCiPressoJsFront::getInstance()->addToBasket($product);
        }
    }

    /**
     * action_type: add_to_cart on listing
     */
    public function addToBasketListing()
    {
        global $product;
        if ($product instanceof WC_Product) {
            WCiPressoJsFront::getInstance()->addToBasketListing($product);
        }
    }

    /**
     * action_type: after_checkout_billing_form
     */
    public function addConsentCheckout()
    {
        if (
            !empty($this->settings[self::$OPTION_CONSENT_ID]) &&
            !empty($this->settings[self::$OPTION_CONSENT_CONTENT])
        ) {
            WCiPressoJsFront::getInstance()
                ->addConsentCheckbox(
                    $this->settings[self::$OPTION_CONSENT_CONTENT],
                    ($_COOKIE[self::$OPTION_CONSENT_AGREE] === 'true')
                );
        }
    }

    /**
     * @param $customerId
     * @return bool
     */
    public function saveCustomer($customerId): bool
    {
        if (!empty($this->settings[self::$OPTION_API_KEY])) {
            try {
                $postData = $this->get_post_data();
                if (!empty($postData[self::$CONSENT_FIELD_ID])) {
                    $consentValue = !empty($postData[self::$CONSENT_FIELD_ID]);
                    $consentId = $this->settings[self::$OPTION_CONSENT_ID];
                }
                $iPressoCustomer = WCiPressoRestApiBuilder::createEditCustomer($customerId, $consentId, $consentValue);
                $newCustomerResponse = WCiPressoRestApi::getInstance($this->settings[self::$OPTION_API_KEY])->sendSaveCustomer($iPressoCustomer);
                if (empty($newCustomerResponse)) {
                    return true;
                }

                setcookie(self::$MONITORING_IMAGE_SESSION, $newCustomerResponse->getMonitoringImage());
                return true;
            } catch (Exception $exception) {
                $this->add_error($exception->getMessage());
                return false;
            }
        }
        return false;
    }

    /**
     * action_type: order
     * @param int $order_id
     * @return bool
     */
    public function purchaseOrder(int $order_id): bool
    {
        if (!empty($this->settings[self::$OPTION_API_KEY])) {
            $wcOrder = new WC_Order($order_id);
            $postData = $this->get_post_data();
            $consentValue = !empty($postData[self::$CONSENT_FIELD_ID]);
            $consentId = $this->settings[self::$OPTION_CONSENT_ID];

            try {
                $order = WCiPressoRestApiBuilder::createOrderRequest($wcOrder, $consentId, $consentValue);
                $orderResponse = WCiPressoRestApi::getInstance($this->settings[self::$OPTION_API_KEY])->sendOrder($order);
                setcookie(self::$OPTION_CONSENT_AGREE, $consentValue ? 'true' : 'false');
                $this->saveConfig();
            } catch (Exception $exception) {
                $this->add_error($exception->getMessage());
                return false;
            }
            setcookie(self::$MONITORING_IMAGE_SESSION, $orderResponse->getMonitoringImage());
            return true;
        }

        return false;
    }

    public function showMonitoringImage()
    {
        $cookiePixel = sanitize_url($_COOKIE[self::$MONITORING_IMAGE_SESSION]);

        if (!empty($cookiePixel)) {
            WCiPressoJsFront::getInstance()->showMonitoringImage($cookiePixel);
            setcookie(self::$MONITORING_IMAGE_SESSION, '');
        }
    }

    public function saveConfig(): bool
    {
        return update_option(
            $this->get_option_key(),
            apply_filters('woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings),
            'yes'
        );
    }
}
