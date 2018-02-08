/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Inchoo_Shipping/js/model/shipping-rates-validator',
        'Inchoo_Shipping/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        inchooShippingRatesValidator,
        inchooShippingRatesValidationRules
    ) {
        'use strict';
        defaultShippingRatesValidator.registerValidator('inchoo', inchooShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('inchoo', inchooShippingRatesValidationRules);
        return Component;
    }
);