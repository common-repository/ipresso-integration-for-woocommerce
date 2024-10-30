<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoNewCustomerRequest implements JsonSerializable
{
    /**
     * @var WCiPressoSimpleCustomer
     */
    private $customer;

    /**
     * @return WCiPressoSimpleCustomer
     */
    public function getCustomer(): WCiPressoSimpleCustomer
    {
        return $this->customer;
    }

    /**
     * @param WCiPressoSimpleCustomer $customer
     * @return WCiPressoNewCustomerRequest
     */
    public function setCustomer(WCiPressoSimpleCustomer $customer): WCiPressoNewCustomerRequest
    {
        $this->customer = $customer;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}