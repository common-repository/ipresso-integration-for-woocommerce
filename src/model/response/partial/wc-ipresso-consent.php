<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoConsent
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $content;

    public static function getConsentFromResponse($data)
    {
        if (
            !empty($data->id) &&
            !empty($data->content)
        ) {
            $consent = new WCiPressoConsent();
            $consent->setId($data->id)
                ->setContent($data->content);

            return $consent;
        } else {
            return null;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return WCiPressoConsent
     */
    public function setId(int $id): WCiPressoConsent
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return WCiPressoConsent
     */
    public function setContent(string $content): WCiPressoConsent
    {
        $this->content = $content;
        return $this;
    }
}