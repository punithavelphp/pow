<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Block\Adminhtml\System\Config;

class Image extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $addonHtml = '<img src="%s" id="' . $element->getHtmlId() . '" height="44" width="44"
            class="small-image-preview v-middle" />';

        $id = explode('_', $element->getHtmlId());
        $imgUrl = $this->getViewFileUrl('Plumrocket_ShippingTracking::images/icons/' . end($id) . '.png');

        return sprintf($addonHtml, $imgUrl);
    }
}
