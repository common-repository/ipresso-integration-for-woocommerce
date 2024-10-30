<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoOrderApi
{
    protected $namespace = 'ipresso/v1/woocommerce';

    public function registerRoutes()
    {
        register_rest_route(
            $this->namespace,
            '/orders',
            [
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'getOrders'],
                    'permission_callback' => [$this, 'apiAccessPermissionsCheck'],
                ]
            ]
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return bool
     */
    public function apiAccessPermissionsCheck(WP_REST_Request $request): bool
    {
        $integrationKey = (string)$request->get_header('api-key');

        return WCiPressoIntegration::getInstance()->checkApiKey($integrationKey);
    }

    public function getOrders(WP_REST_Request $request): WP_REST_Response
    {
        $integration = new WCiPressoIntegration();
        $query = $request->get_query_params();
        $limit = $query['limit'] ?? 25;
        $page = $query['page'] ?? 1;
        $date = $query['date'] ?? time();

        /** @var stdClass $result */
        $result = wc_get_orders([
            'paginate' => true,
            'limit' => $limit,
            'paged' => $page,
            'orderby' => $date,
            'order' => 'DESC',
            'date_created' => '<' . $date,
        ]);

        $consentId = $integration->getSetting(WCiPressoIntegration::$OPTION_CONSENT_ID, 0);
        $ordersDto = array_map(function (WC_Order $order) use ($consentId) {
            return WCiPressoRestApiBuilder::createOrderRequest($order, $consentId, false);
        }, $result->orders);

        $dto = [
            'orders' => $ordersDto,
            'allOrders' => $result->total,
            'allPages' => $result->max_num_pages
        ];

        return new WP_REST_Response(json_decode(json_encode($dto)), 200);
    }
}
