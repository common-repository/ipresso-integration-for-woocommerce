<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoCustomer implements JsonSerializable
{
    /** @var string */
    public $id;
    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;
    /** @var string */
    public $email;
    /** @var array */
    public $consent;
    /** @var WCiPressoAddress */
    public $address;

    public static function create($customer, WCiPressoAddress $address = null): WCiPressoCustomer
    {
        $iCustomer = new WCiPressoCustomer();
        $iCustomer
            ->setId($customer->get_id())
            ->setEmail($customer->get_email())
            ->setFirstName($customer->get_first_name())
            ->setLastName($customer->get_last_name());
        if ($address) {
            $iCustomer
                ->setAddress($address);
        }
        return $iCustomer;
    }

    /** @return string */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $id
     * @return WCiPressoCustomer
     */
    public function setId(string $id): WCiPressoCustomer
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return WCiPressoCustomer
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $firstName
     * @return WCiPressoCustomer
     */
    public function setFirstName(string $firstName): WCiPressoCustomer
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return WCiPressoCustomer
     */
    public function setLastName(string $lastName): WCiPressoCustomer
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return WCiPressoCustomer
     */
    public function setEmail(string $email): WCiPressoCustomer
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return WCiPressoAddress
     */
    public function getAddress(): WCiPressoAddress
    {
        return $this->address;
    }

    /**
     * @param WCiPressoAddress $address
     * @return WCiPressoCustomer
     */
    public function setAddress(WCiPressoAddress $address): WCiPressoCustomer
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return WCiPressoAddress
     */
    public function getConsent(): array
    {
        return $this->consent;
    }

    /**
     * @param WCiPressoconsent $consent
     * @return WCiPressoCustomer
     */
    public function setConsent(array $consent): WCiPressoCustomer
    {
        $this->consent = $consent;
        return $this;
    }

    public function jsonSerialize()
    {
        return $this;
    }
}
