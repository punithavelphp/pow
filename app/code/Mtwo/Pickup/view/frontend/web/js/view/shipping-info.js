;define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote'

], function (Component, ko, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mtwo_Pickup/shipping-info'
        },

        initObservable: function () {
            var self = this._super();
            this.getOpeningHours = ko.computed(function() {
               

                return window.checkoutConfig.opening_hours;

            }, this);
			
			
			 this.showFreeShippingInfo = ko.computed(function() {
                var method = quote.shippingMethod();

                if(method && method['carrier_code'] !== undefined) {
                    if(method['carrier_code'] === 'storepickup') {
                        return true;
                    }
                }

                return false;

            }, this);
            this.showTableRateShippingInfo = ko.computed(function() {
                var method = quote.shippingMethod();

                if(method && method['carrier_code'] !== undefined) {
                    if(method['carrier_code'] === 'tablerate') {
                        return true;
                    }
                }

                return false;

            }, this);

            return this;
        }
    });
});
