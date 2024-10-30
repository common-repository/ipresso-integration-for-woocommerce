<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoProduct implements JsonSerializable
{
    /** @var string */
    public $integrationName;
    /** @var string */
    public $productId;
    /** @var string */
    public $productName;
    /** @var string */
    public $productImageUrl;
    /** @var double */
    public $productPrice;
    /** @var string */
    public $productUrl;
    /** @var int */
    public $productQuantity;
    /** @var double */
    public $productTotalPrice;
    /** @var bool */
    public $purchasePaid;
    /** @var string */
    public $purchaseOrderId;

    /**
     * @return string
     */
    public function getIntegrationName(): string
    {
        return $this->integrationName;
    }

    /**
     * @param string $integrationName
     * @return WCiPressoProduct
     */
    public function setIntegrationName(string $integrationName): WCiPressoProduct
    {
        $this->integrationName = $integrationName;
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
     * @return WCiPressoProduct
     */
    public function setProductId(string $productId): WCiPressoProduct
    {
        $this->productId = $productId;
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
     * @return WCiPressoProduct
     */
    public function setProductName(string $productName): WCiPressoProduct
    {
        $this->productName = $productName;
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
     * @return WCiPressoProduct
     */
    public function setProductImageUrl(string $productImageUrl): WCiPressoProduct
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
     * @return WCiPressoProduct
     */
    public function setProductPrice(float $productPrice): WCiPressoProduct
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
     * @return WCiPressoProduct
     */
    public function setProductUrl(string $productUrl): WCiPressoProduct
    {
        $this->productUrl = $productUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }

    /**
     * @param int $productQuantity
     * @return WCiPressoProduct
     */
    public function setProductQuantity(int $productQuantity): WCiPressoProduct
    {
        $this->productQuantity = $productQuantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getProductTotalPrice(): float
    {
        return $this->productTotalPrice;
    }

    /**
     * @param float $productTotalPrice
     * @return WCiPressoProduct
     */
    public function setProductTotalPrice(float $productTotalPrice): WCiPressoProduct
    {
        $this->productTotalPrice = $productTotalPrice;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPurchasePaid(): bool
    {
        return $this->purchasePaid;
    }

    /**
     * @param bool $purchasePaid
     * @return WCiPressoProduct
     */
    public function setPurchasePaid(bool $purchasePaid): WCiPressoProduct
    {
        $this->purchasePaid = $purchasePaid;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurchaseOrderId(): string
    {
        return $this->purchaseOrderId;
    }

    /**
     * @param string $purchaseOrderId
     * @return WCiPressoProduct
     */
    public function setPurchaseOrderId(string $purchaseOrderId): WCiPressoProduct
    {
        $this->purchaseOrderId = $purchaseOrderId;
        return $this;
    }

    public function jsonSerialize()
    {
        return $this;
    }
}
