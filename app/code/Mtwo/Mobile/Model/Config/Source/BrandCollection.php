<?php

namespace Mtwo\Mobile\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class BrandCollection implements ArrayInterface {

    protected $brandCollection;

    public function __construct(
    \Ves\Brand\Model\BrandFactory $brandCollection
    ) {
        $this->brandCollection = $brandCollection;
    }

    public function getBrandCollection() {
        $collection = $this->brandCollection->create()->getCollection()->addFieldToFilter('status', 1);
         return $collection;
    }

    public function toOptionArray() {
        $arr = $this->_toArray();
        $ret = [];
        $ret[] = ['value' => '', 'label' => 'Select'];

        foreach ($arr as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    private function _toArray() {
        $brandCollection = $this->getBrandCollection();

        $brandList = array();
        foreach ($brandCollection as $brand) {
            $brandList[$brand->getBrandId()] = $brand->getName();
        }

        return $brandList;
    }

}
