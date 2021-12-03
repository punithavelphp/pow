<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_BannerSlider
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BannerSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Location
 * @package Mageplaza\BannerSlider\Model\Config\Source
 */
class Category implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$collection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory')->create();
		$categoryModel = $objectManager->get('Magento\Catalog\Model\Category');
		$collection->addAttributeToFilter('level', array('eq' => 2))->addAttributeToFilter('is_active','1');
		$options = [['label' => '', 'value' => '']];
		foreach($collection as $categoryData) {
			$category = $categoryModel->load($categoryData->getEntityId());
			$options[] = [
					'label' => $category->getName(),
					'value' => $category->getId()
				];
		}
        return $options;
    }
}
