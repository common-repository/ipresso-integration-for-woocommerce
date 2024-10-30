<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoOrderRequest implements JsonSerializable
{
    /** @var string */
    public $integrationName;
    /** @var string */
    public $orderId;
    /** @var string[] */
    public $productsIds;
    /** @var int $orderDate */
    public $orderDate;
    /** @var string[] */
    public $productsNames;
    /** @var bool */
    public $paid;
    /** @var double */
    public $totalPrice;
    /** @var WCiPressoProduct[] */
    public $products;
    /** @var WCiPressoCustomer */
    public $customer;
    /** @var array */
    public $consents;

    /**
     * @return WCiPressoProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param WCiPressoProduct[] $products
     * @return WCiPressoOrderRequest
     */
    public function setProducts(array $products): WCiPressoOrderRequest
    {
        $this->products = $products;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return WCiPressoOrderRequest
     */
    public function setOrderId(string $orderId): WCiPressoOrderRequest
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     * @return WCiPressoOrderRequest
     */
    public function setTotalPrice(float $totalPrice): WCiPressoOrderRequest
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @param bool $paid
     * @return WCiPressoOrderRequest
     */
    public function setPaid(bool $paid): WCiPressoOrderRequest
    {
        $this->paid = $paid;
        return $this;
    }

    /**
     * @return WCiPressoCustomer
     */
    public function getCustomer(): WCiPressoCustomer
    {
        return $this->customer;
    }

    /**
     * @param WCiPressoCustomer $customer
     * @return WCiPressoOrderRequest
     */
    public function setCustomer(WCiPressoCustomer $customer): WCiPressoOrderRequest
    {
        $this->customer = $customer;
        return $this;
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
     * @return WCiPressoOrderRequest
     */
    public function setIntegrationName(string $integrationName): WCiPressoOrderRequest
    {
        $this->integrationName = $integrationName;
        return $this;
    }

    /**
     * @return array
     */
    public function getProductsIds(): array
    {
        return $this->productsIds;
    }

    /**
     * @param array $productsIds
     * @return WCiPressoOrderRequest
     */
    public function setProductsIds(array $productsIds): WCiPressoOrderRequest
    {
        $this->productsIds = $productsIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getProductsNames(): array
    {
        return $this->productsNames;
    }

    /**
     * @param array $productsNames
     * @return WCiPressoOrderRequest
     */
    public function setProductsNames(array $productsNames): WCiPressoOrderRequest
    {
        $this->productsNames = $productsNames;
        return $this;
    }

    /**
     * @return array
     */
    public function getConsents(): array
    {
        return $this->consents;
    }

    /**
     * @param array $consents
     * @return WCiPressoOrderRequest
     */
    public function setConsents(array $consents): WCiPressoOrderRequest
    {
        $this->consents = $consents;
        return $this;
    }

    public function jsonSerialize()
    {
        return $this;
    }

    public function getOrderDate(): int
    {
        return $this->orderDate;
    }

    public function setOrderDate(int $orderDate): self
    {
        $this->orderDate = $orderDate;
        return $this;
    }
}
