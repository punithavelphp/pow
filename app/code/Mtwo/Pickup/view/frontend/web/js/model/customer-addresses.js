/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'ko',
    'Magento_Customer/js/model/customer/address'
], function ($, ko, Address) {
    'use strict';

    var isLoggedIn = ko.observable(window.isCustomerLoggedIn);

    return {
        /**
         * @return {Array}
         */
        getAddressItems: function () {
            var items = [],
                customerData = window.customerData;

            if (isLoggedIn()) {
                if (Object.keys(customerData).length) {
                    $.each(customerData.addresses, function (key, item) {
						console.log(item);
                        items.push(new Address(item));
                    });
                }
            }
 			var newItem = window.checkoutConfig.shopAddress;
  		  items.push(new Address(window.checkoutConfig.shopAddress));
			 
            return items;
        }
    };
});
