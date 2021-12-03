<?php 
namespace Mtwo\Mobile\Api;
 
interface LayeredSearchInterface {
 
    /**
     * Get  searchLayeredFilter
     *
     * @param int $category
     * @return string $items
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function searchLayeredFilter($category);
}
