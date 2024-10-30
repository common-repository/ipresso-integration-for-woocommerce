<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoRestApiBuilder
{
    private static $SEPARATOR = ',';


    /**
     * @param $email
     * @param int $consentId
     * @param bool $consentValue
     * @return WCiPressoNewCustomerRequest
     */
    public static function createNewCustomerRequest(
        $email,
        int $consentId,
        bool $consentValue,
        string $id
    ): WCiPressoNewCustomerRequest {
        $consents = [];
        $consents[$consentId] = $consentValue;

        $simpleCustomer = new WCiPressoSimpleCustomer();
        $simpleCustomer
            ->setConsents($consents)
            ->setEmail($email)
            ->setId($id);

        $customer = new WCiPressoNewCustomerRequest();
        $customer->setCustomer($simpleCustomer);

        return $customer;
    }

    public static function createEditCustomer($customerId, $consentId = null, $consentValue = null): WCiPressoCustomer
    {

        $customer = new WC_Customer($customerId);
        $address = WCiPressoAddress::create($customer);
        $iPressoCustomer = WCiPressoCustomer::create($customer, $address);
        if ($consentId !== null) {
            $consents[$consentId] = $consentValue;
            $iPressoCustomer->setConsent($consents);
        }

        return $iPressoCustomer;
    }

    /**
     * @param WC_Order $wcOrder
     * @param int $consentId
     * @param bool $consentValue
     * @return WCiPressoOrderRequest
     */
    public static function createOrderRequest(
        WC_Order $wcOrder,
        int $consentId,
        bool $consentValue
    ): WCiPressoOrderRequest {
        $order = new WCiPressoOrderRequest();
        $products = self::createProductsListFromOrder($wcOrder);
        $customer = self::createCustomerFromOrder($wcOrder);
        $consents = [];
        $consents[$consentId] = $consentValue;
        $orderDate = $wcOrder->get_date_paid() ?: $wcOrder->get_date_created();
        $order
            ->setOrderDate($orderDate ? $orderDate->getTimestamp() : time())
            ->setIntegrationName(WCiPressoConfig::$INTEGRATION_NAME)
            ->setOrderId($wcOrder->get_id())
            ->setTotalPrice($wcOrder->get_total())
            ->setPaid($wcOrder->is_paid())
            ->setProducts($products)
            ->setProductsIds(self::getProductsIdsList($products))
            ->setProductsNames(self::getProductsNamesList($products))
            ->setCustomer($customer)
            ->setConsents($consents);

        return $order;
    }

    /**
     * @param WC_Order $wcOrder
     * @return WCiPressoProduct[]
     */
    private static function createProductsListFromOrder(WC_Order $wcOrder): array
    {
        $wcProducts = $wcOrder->get_items();
        /** @var WCiPressoProduct[] $products */
        $products = [];

        foreach ($wcProducts as $wcProduct) {
            $products[] = self::createProductFromOrder($wcProduct);
        }

        return $products;
    }

    private static function createProductFromOrder(WC_Order_Item $wcProductInOrder): WCiPressoProduct
    {
        $product = new WCiPressoProduct();
        $wcOrder = $wcProductInOrder->get_order();
        $wcItemProduct = new WC_Order_Item_Product($wcProductInOrder->get_id());
        $wcProduct = $wcItemProduct->get_product();
        $productImages = wp_get_attachment_image_src(
            get_post_thumbnail_id($wcProductInOrder->get_id()),
            'single-post-thumbnail'
        );

        $product->setIntegrationName(WCiPressoConfig::$INTEGRATION_NAME)
            ->setProductId((string)$wcProduct->get_id())
            ->setProductName($wcProductInOrder->get_name())
            ->setProductPrice($wcProduct->get_price())
            ->setProductTotalPrice($wcOrder->get_item_total($wcProduct))
            ->setProductQuantity($wcProductInOrder->get_quantity())
            ->setProductUrl(get_permalink($wcProduct->get_id()))
            ->setProductImageUrl($productImages ? $productImages[0] : '')
            ->setPurchasePaid($wcOrder->is_paid())
            ->setPurchaseOrderId($wcOrder->get_id());

        return $product;
    }

    private static function createCustomerFromOrder(WC_Order $wcOrder): WCiPressoCustomer
    {
        $customer = new WCiPressoCustomer();
        $customer
            ->setId($wcOrder->get_customer_id())
            ->setFirstName($wcOrder->get_billing_first_name())
            ->setLastName($wcOrder->get_billing_last_name())
            ->setEmail($wcOrder->get_billing_email())
            ->setAddress(self::createCustomerAddressFromOrder($wcOrder));

        return $customer;
    }

    /**
     * @param WC_Order $wcOrder
     * @return WCiPressoAddress
     */
    private static function createCustomerAddressFromOrder(WC_Order $wcOrder): WCiPressoAddress
    {
        $address = new WCiPressoAddress();
        $address->setCountry($wcOrder->get_billing_country())
            ->setPostCode($wcOrder->get_billing_postcode())
            ->setCity($wcOrder->get_billing_city())
            ->setStreet($wcOrder->get_billing_address_1() . ' ' . $wcOrder->get_billing_address_2())
            ->setMobile($wcOrder->get_billing_phone())
            ->setCompany($wcOrder->get_billing_company());

        return $address;
    }

    /**
     * @param WCiPressoProduct[] $products
     * @return array
     */
    private static function getProductsIdsList(array $products): array
    {
        $ids = [];

        foreach ($products as $product) {
            $ids[] = $product->getProductId();
        }

        return $ids;
    }

    /**
     * @param WCiPressoProduct[] $products
     * @return array
     */
    private static function getProductsNamesList(array $products): array
    {
        $names = [];

        foreach ($products as $product) {
            $names[] = $product->getProductName();
        }

        return $names;
    }
}
