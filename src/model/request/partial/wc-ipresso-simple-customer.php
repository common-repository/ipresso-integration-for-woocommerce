<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoSimpleCustomer implements JsonSerializable
{
    /** @var string */
    public $id;
    /**
     * @var string
     */
    public $email;
    /**
     * @var array
     */
    public $consents;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return WCiPressoSimpleCustomer
     */
    public function setEmail(string $email): WCiPressoSimpleCustomer
    {
        $this->email = $email;
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
     * @return WCiPressoSimpleCustomer
     */
    public function setConsents(array $consents): WCiPressoSimpleCustomer
    {
        $this->consents = $consents;
        return $this;
    }

    public function jsonSerialize()
    {
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId(string $id): WCiPressoSimpleCustomer
    {
        $this->id = $id;
        return $this;
    }
}
