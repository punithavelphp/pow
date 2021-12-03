<?php

/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mtwo\Mobile\Api;

/**
 * Interface for managing customer groups.
 * @api
 * @since 100.0.2
 */
interface ConfigInterface {

    /**
     * Get  Product Blocks.
     *
     * @param int $lang
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getConfig($lang = 0);
	
	 /**
     * Get  Country Region
     *
     * @param string $countrycode
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRegionsOfCountry($countrycode);
	
	
	
    /**
     * Get  Product Blocks.
     *
     * @param int $invoiceId
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getInvoice($invoiceId);
	
 
	  
}
