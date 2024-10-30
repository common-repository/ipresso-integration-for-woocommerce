<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoJsFront
{
    /** @var WCiPressoJsFront */
    private static $_instance;

    public static function getInstance(): WCiPressoJsFront
    {
        return null === self::$_instance ? (self::$_instance = new self()) : self::$_instance;
    }

    public function injectMonitoringCode($monitoringCode)
    {
        wc_enqueue_js($monitoringCode);
    }

    public function addToBasket(WC_Product $product)
    {
        $addToBasketModel = $this->createAddToBasket($product);

        wc_enqueue_js(WCiPressoJsApiBuilder::addToBasketJS($addToBasketModel, '.add_to_cart_button'));
    }

    /**
     * @param WC_Product $product
     * @return WCiPressoAddToBasketRequest
     */
    private function createAddToBasket(WC_Product $product): WCiPressoAddToBasketRequest
    {
        $addToBasketModel = new WCiPressoAddToBasketRequest();

        $productImages = wp_get_attachment_image_src(
            get_post_thumbnail_id($product->get_id()),
            'single-post-thumbnail'
        );
        $productUrl = get_permalink($product->get_id());

        $addToBasketModel->setProductId($product->get_id())
            ->setProductName($product->get_name())
            ->setProductPrice($product->get_price())
            ->setProductImageUrl($productImages ? $productImages[0] : '')
            ->setProductUrl($productUrl)
            ->setShopDomain(get_site_url());

        return $addToBasketModel;
    }

    public function addToBasketListing(WC_Product $product)
    {
        $addToBasketModel = $this->createAddToBasket($product);

        wc_enqueue_js(
            WCiPressoJsApiBuilder::addToBasketJS(
                $addToBasketModel,
                '.products .post-' . sanitize_text_field($product->get_id()) . ' .add_to_cart_button'
            )
        );
    }

    /**
     * @param string $monitoringImageLink
     * @return void
     */
    public function showMonitoringImage(string $monitoringImageLink)
    {
        $monitoringImageLink = sanitize_url($monitoringImageLink);

        wc_enqueue_js(
            "
            let monitoringImage = document.createElement('img');
            monitoringImage.style.cssText = 'width:1px;height:1px;display:none;';
            monitoringImage.src = '$monitoringImageLink';
            document.body.append(monitoringImage);
        "
        );
    }

    public function addConsentCheckbox(string $consentContent, ?bool $agree = false)
    {
        $fieldId = WCiPressoIntegration::$CONSENT_FIELD_ID;
        $checked = $agree ? 'checked ' : '';
        $consentElementContent =  '
            <p class="form-row form-row-wide ipresso-newsletter">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="' . sanitize_text_field($fieldId) . '" type="checkbox" ' . $checked .     'name="' . sanitize_text_field($fieldId) . '">
                <label for="' . sanitize_text_field($fieldId) . '" class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                <span>' . sanitize_text_field($consentContent) . '</span>
                </label>
            </p>
        ';

        echo(
            wp_kses(
                $consentElementContent,
                [
                    'p' => [
                        'class' => []
                    ],
                    'input' => [
                        'id' => [],
                        'class' => [],
                        'type' => [],
                        'name' => [],
                        'checked' => []
                    ],
                    'label' => [
                      'for' => [],
                      'class' => []
                    ],
                    'span' => [],
                    'br' => [],
                    'b' => [],
                    'strong' => [],
                ]
            )
        );
    }
}
