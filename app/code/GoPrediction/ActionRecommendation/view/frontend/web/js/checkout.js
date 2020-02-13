define([
    'goPredictionApi'
], function (goPredictionApi) {
    'use strict';

    return function (config) {
        goPredictionApi.publicAccessKey = config.publicAccessKey;

        for (var i = 0; i < window.checkoutConfig.quoteItemData.length; i++) {
            goPredictionApi.purchase({
                customer: goPredictionApi._getCustomerIdentifier(),
                product_id: window.checkoutConfig.quoteItemData[i].product_id,
                name: window.checkoutConfig.quoteItemData[i].name,
                count: window.checkoutConfig.quoteItemData[i].qty,
                price: Math.trunc(window.checkoutConfig.quoteItemData[i].price_incl_tax * 100)
            });
        }
    }
});
