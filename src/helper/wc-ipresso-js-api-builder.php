<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCiPressoJsApiBuilder
{

    static public function addToBasketJS(WCiPressoAddToBasketRequest $addToBasketModel, string $triggerSelector): string
    {
        $addToBasketParameters = $addToBasketModel->toRequestArray();
        $activityKey = WCiPressoAddToBasketRequest::$ACTIVITY_KEY;

        $triggerSelector = sanitize_text_field($triggerSelector);


        return self::encapsulate(
            "
        if (document.querySelector('$triggerSelector') !== null) {
            document.querySelector('$triggerSelector').addEventListener('click', function () {
                let activityData = " . json_encode($addToBasketParameters) . ";
                if (document.querySelector('$triggerSelector').parentElement.querySelector('.qty') !== null) {
                    activityData.productQuantity = parseInt(document.querySelector('$triggerSelector').parentElement.querySelector('.qty').value)
                }
                __ipSaveActivity('$activityKey', activityData);
            });
        }"
        );
    }

    static private function encapsulate(string $jsCode): string
    {
        return "(function() { $jsCode })();";
    }

}