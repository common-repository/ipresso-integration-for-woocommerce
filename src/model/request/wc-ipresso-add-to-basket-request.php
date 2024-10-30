<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoAddToBasketRequest
{

    public static $ACTIVITY_KEY = 'integration_add_product_to_basket';

    /**
     * @var string
     */
    private $integrationName = 'woocommerce';
    /**
     * @var string
     */
    private $productName;
    /**
     * @var string
     */
    private $productId;
    /**
     * @var string
     */
    private $productImageUrl;
    /**
     * @var string
     */
    private $productUrl;
    /**
     * @var double
     */
    private $productPrice;
    /**
     * @var int|null
     */
    private $productQuantity;
    /**
     * @var string
     */
    private $shopDomain;

    public function toRequestArray(): array
    {
        return [
            'integration_name' => $this->getIntegrationName(),
            'integration_product_name' => $this->getProductName(),
            'product_id' => $this->getProductId(),
            'product_image_url' => $this->getProductImageUrl(),
            'product_price' => $this->getProductPrice(),
            'product_url' => $this->getProductUrl(),
            'productQuantity' => $this->getProductQuantity(),
            'shop_domain' => $this->getShopDomain()
        ];
    }

    /**
     * @return string
     */
    public function getIntegrationName(): string
    {
        return $this->integrationName;
    }

    /**
     * @param string $integrationName
     * @return WCiPressoAddToBasketRequest
     */
    public function setIntegrationName(string $integrationName): WCiPressoAddToBasketRequest
    {
        $this->integrationName = $integrationName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductName(string $productName): WCiPressoAddToBasketRequest
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductId(string $productId): WCiPressoAddToBasketRequest
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductImageUrl(): string
    {
        return $this->productImageUrl;
    }

    /**
     * @param string $productImageUrl
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductImageUrl(string $productImageUrl): WCiPressoAddToBasketRequest
    {
        $this->productImageUrl = $productImageUrl;
        return $this;
    }

    /**
     * @return float
     */
    public function getProductPrice(): float
    {
        return $this->productPrice;
    }

    /**
     * @param float $productPrice
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductPrice(float $productPrice): WCiPressoAddToBasketRequest
    {
        $this->productPrice = $productPrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductUrl(): string
    {
        return $this->productUrl;
    }

    /**
     * @param string $productUrl
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductUrl(string $productUrl): WCiPressoAddToBasketRequest
    {
        $this->productUrl = $productUrl;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductQuantity()
    {
        return $this->productQuantity;
    }

    /**
     * @param int $productQuantity
     * @return WCiPressoAddToBasketRequest
     */
    public function setProductQuantity(int $productQuantity): WCiPressoAddToBasketRequest
    {
        $this->productQuantity = $productQuantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getShopDomain(): string
    {
        return $this->shopDomain;
    }

    /**
     * @param string $shopDomain
     * @return WCiPressoAddToBasketRequest
     */
    public function setShopDomain(string $shopDomain): WCiPressoAddToBasketRequest
    {
        $this->shopDomain = $shopDomain;
        return $this;
    }
}