/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/model/resource-url-manager',
    'Magento_Checkout/js/model/quote',
    'mage/storage',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/model/error-processor',
	 'Magento_Checkout/js/model/shipping-rate-processor/new-address'

], function (resourceUrlManager, quote, storage, shippingService, rateRegistry, errorProcessor,newAddress) {
    'use strict';

    return {
        /**
         * @param {Object} address
         */
        getRates: function (address) {
            var cache;

            shippingService.isLoading(true);
            cache = rateRegistry.get(address.getKey());

            if (cache) {
                shippingService.setShippingRates(cache);
                shippingService.isLoading(false);
            } else {
				  console.log(address);
				if(typeof address.customerAddressId == 'undefined'){
 					 address.saveInAddressBook= 0;
					address.sameAsBilling = 0;
					address.extensionAttributes = {};
				for(var a in address.customAttributes){
					address.extensionAttributes[address.customAttributes[a].attribute_code] = address.customAttributes[a].value;
				}
  					newAddress.getRates(address);
				}else{
                storage.post(
                    resourceUrlManager.getUrlForEstimationShippingMethodsByAddressId(),
                    JSON.stringify({
                        addressId: address.customerAddressId
                    }),
                    false
                ).done(function (result) {
                    rateRegistry.set(address.getKey(), result);
                    shippingService.setShippingRates(result);
                }).fail(function (response) {
                    shippingService.setShippingRates([]);
                    errorProcessor.process(response);
                }).always(function () {
                    shippingService.isLoading(false);
                }
                );
			 
			}
				
            }
        }
    };
});
