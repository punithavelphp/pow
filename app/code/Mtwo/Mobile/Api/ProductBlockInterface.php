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
interface ProductBlockInterface {

    /**
     * Get  Product Blocks.
     *
     * @param int $blockId
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductBlocks($blockId = 1);

    /**
     * Get Banner Slider.
     *
     * @param int $blockId
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMobileBannerSlider($blockId = 1);

    /**
     * Get Banner.
     *
     * @param int $blockId
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMobileBanner($blockId = 1);
	  
}
