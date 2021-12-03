<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MageArray\Testimonials\Model;

class Testimonials extends \Magento\Framework\Model\AbstractModel
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    public function _construct()
    {

        $this->_init(\MageArray\Testimonials\Model\ResourceModel\Testimonials::Class);
    }

    /* public function checkUrlKey($urlKey)
    {
        return $this->_getResource()->checkUrlKey($urlKey);
    } */
}
