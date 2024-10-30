<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class WCiPressoRestApi
{
    /** @var WCiPressoRestApi */
    private static $_instance;
    private static $AUTHORIZATION = 'woocommerce/plugin/activation';
    private static $ORDER = 'woocommerce/order';
    private static $CUSTOMER = 'woocommerce/customer';
    private static $apiKey;

    public function __construct(string $apiKey = null)
    {
        self::$apiKey = $apiKey;
    }

    public static function getInstance(string $apiKey = null): WCiPressoRestApi
    {
        return (null === self::$_instance || self::$apiKey !== $apiKey) ? (self::$_instance = new self($apiKey)) : self::$_instance;
    }

    /**
     * @throws Exception
     */
    public function sendSaveCustomer($customer)
    {
        $customerEndpoint = WCiPressoConfig::$REST_API_ENDPOINT . self::$CUSTOMER;

        try {
            $response = $this->sendPostRequest($customerEndpoint, $customer);

            if ($response['body']) {
                $responseData = json_decode($response['body'], true);

                if (empty($responseData)) {
                    return null;
                }
                $monitoringPixel = $responseData['monitoringImage'] ?? '';
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        $newCustomerResponse = new WCiPressoNewCustomerResponse();
        $newCustomerResponse->setMonitoringImage($monitoringPixel);

        return $newCustomerResponse;
    }

    /**
     * @param string $requestUrl
     * @param JsonSerializable $requestData
     * @return array
     * @throws Exception
     */
    private function sendPostRequest(string $requestUrl, \JsonSerializable $requestData): array
    {
        $json = wp_json_encode($requestData);

        $response = wp_remote_post($requestUrl, [
            'body' => $json,
            'headers' => [
                'token' => self::$apiKey,
                'woocommerceVersion' => WCiPressoConfig::getWordpressVersion(),
                'content-type' => 'application/json',
                'moduleVersion' => WCiPressoConfig::$VERSION
            ]
        ]);

        if (
            is_a($response, WP_Error::class) || ($response['response']['code'] != 200 &&
                $response['response']['code'] != 201
            )
        ) {
            if (is_a($response, WP_Error::class)) {
                $responseError = (string)$response->get_error_code();
            } else {
                $responseError = (string)$response['response']['code'];
            }

            throw new Exception($responseError);
        } else {
            return $response;
        }
    }

    /**
     * @param array $response
     * @return stdClass
     * @throws Exception
     */
    private function getResponseData(array $response): stdClass
    {
        if (
            $response['body'] &&
            $responseData = json_decode($response['body'])
        ) {
            return $responseData;
        } else {
            throw new Exception('Body content missing in response');
        }
    }

    /**
     * @param WCiPressoOrderRequest $order
     * @return WCiPressoOrderResponse
     * @throws Exception
     */
    public function sendOrder(WCiPressoOrderRequest $order): WCiPressoOrderResponse
    {
        $orderEndpoint = WCiPressoConfig::$REST_API_ENDPOINT . self::$ORDER;

        try {
            $response = $this->sendPostRequest($orderEndpoint, $order);
            $responseData = $this->getResponseData($response);
            if (!$monitoringPixel = $responseData->monitoringImage) {
                throw new Exception('Monitoring pixel is missing in response');
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        $orderResponse = new WCiPressoOrderResponse();
        $orderResponse->setMonitoringImage($monitoringPixel);

        return $orderResponse;
    }

    /**
     * @param WCiPressoAuthorizationRequest $auth
     * @return WCiPressoAuthorizationResponse
     * @throws Exception
     */
    public function sendAuthorize(WCiPressoAuthorizationRequest $auth): WCiPressoAuthorizationResponse
    {
        $enableIntegrationEndpoint = WCiPressoConfig::$REST_API_ENDPOINT . self::$AUTHORIZATION;

        try {
            $response = $this->sendPostRequest($enableIntegrationEndpoint, $auth);
            $responseData = $this->getResponseData($response);

            if (!$monitoringCode = $responseData->monitoringCode) {
                throw new Exception('Monitoring code is missing in response');
            }

            if (!$consentData = $responseData->consent) {
                throw new Exception('Consent is missing in response');
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        $consent = WCiPressoConsent::getConsentFromResponse($consentData);

        $authorizeResponse = new WCiPressoAuthorizationResponse();
        $authorizeResponse->setMonitoringCode($monitoringCode)
            ->setConsent($consent);

        return $authorizeResponse;
    }
}
