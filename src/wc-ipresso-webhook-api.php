<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoWebhookApi
{
    protected $namespace = 'ipresso/v1/woocommerce';

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/integration',
            [
                [
                    'methods' => 'DELETE',
                    'callback' => [$this, 'deleteIntegration'],
                    'permission_callback' => [$this, 'apiAccessPermissionsCheck'],
                ]
            ]
        );

        register_rest_route(
            $this->namespace,
            '/consent',
            [
                [
                    'methods' => 'POST',
                    'callback' => [$this, 'changeConsent'],
                    'permission_callback' => [$this, 'apiAccessPermissionsCheck'],
                ]
            ]
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool
     */
    public function apiAccessPermissionsCheck(WP_REST_Request $request)
    {
        $integrationKey = (string)$request->get_header('api-key');

        return WCiPressoIntegration::getInstance()->checkApiKey($integrationKey);
    }

    /**
     * @return array of WP_REST_Response | WP_Error
     */
    public function deleteIntegration(): array
    {
        if (WCiPressoIntegration::getInstance()->deleteIntegration()) {
            return [
                new WP_REST_Response(null, 200)
            ];
        } else {
            http_response_code(406);
            exit;
        }
    }

    /**
     * @return array of WP_REST_Response | WP_Error
     */
    public function changeConsent(WP_REST_Request $request): array
    {
        $consentId = (string)$request->get_json_params()['consentId'];
        $consentContent = stripslashes((string)$request->get_json_params()['consentContent']);

        if (!empty($consentId) && !empty($consentContent)) {
            if (WCiPressoIntegration::getInstance()->changeConsent($consentId, $consentContent)) {
                return [
                    new WP_REST_Response(null, 200)
                ];
            } else {
                http_response_code(406);
                exit;
            }
        } else {
            http_response_code(400);
            exit;
        }
    }
}
