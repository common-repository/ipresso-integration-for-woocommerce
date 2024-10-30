<?php
if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoAddress implements JsonSerializable
{

    /** @var string */
    public $country;
    /** @var string */
    public $postCode;
    /** @var string */
    public $city;
    /** @var string */
    public $street;
    /** @var string */
    public $mobile;
    /** @var string */
    public $company;


    public static function create($customer): WCiPressoAddress
    {
        $address = new WCiPressoAddress();
        $address
            ->setCity($customer->get_city())
            ->setCompany($customer->get_billing_company())
            ->setCountry($customer->get_billing_country())
            ->setMobile($customer->get_billing_phone())
            ->setPostCode($customer->get_billing_postcode())
            ->setStreet(rtrim($customer->get_billing_address_1() . '' . $customer->get_billing_address_2()));
        return $address;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return WCiPressoAddress
     */
    public function setCountry(string $country): WCiPressoAddress
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     * @return WCiPressoAddress
     */
    public function setPostCode(string $postCode): WCiPressoAddress
    {
        $this->postCode = $postCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return WCiPressoAddress
     */
    public function setCity(string $city): WCiPressoAddress
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return WCiPressoAddress
     */
    public function setStreet(string $street): WCiPressoAddress
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return WCiPressoAddress
     */
    public function setMobile(string $mobile): WCiPressoAddress
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return WCiPressoAddress
     */
    public function setCompany(string $company): WCiPressoAddress
    {
        $this->company = $company;
        return $this;
    }

    public function jsonSerialize()
    {
        return $this;
    }
}
