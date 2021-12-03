<?php
/**
 * Attribute add/edit form options tab
 *
 */

namespace AL\CustomerAttribute\Block\Adminhtml\Attribute\Edit\Options;

class Labels extends \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Labels
{
    /**
     * @var string
     */
    protected $_template = 'AL_CustomerAttribute::customer/attribute/labels.phtml';

    /**
     * Retrieve attribute object from registry
     *
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     * @codeCoverageIgnore
     */
    private function getAttributeObject()
    {
        return $this->_registry->registry('entity_attribute');
    }
}
