<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageArray\Testimonials\Model\ResourceModel\Testimonials;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \MageArray\Testimonials\Model\Testimonials::Class,
            \MageArray\Testimonials\Model\ResourceModel\Testimonials::Class
        );
    }
}
