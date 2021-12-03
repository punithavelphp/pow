<?php
/**
 * Attribute add/edit form options tab
 *
 */

namespace AL\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Options;

use Magento\Store\Model\ResourceModel\Store\Collection;

class Options extends \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options
{
    /**
     * @var string
     */
    protected $_template = 'AL_CustomerAttribute::customer/attribute/options.phtml';
}
