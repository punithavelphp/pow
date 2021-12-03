<?php

namespace Mtwo\Mobile\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Mageplaza\BannerSlider\Helper\Data as bannerHelper;

class BrandSlider implements ArrayInterface {

    public $helperData;

    public function __construct(
    bannerHelper $helperData
    ) {
        $this->helperData = $helperData;
    }

    public function getBannerCollection() {
        $collection = $this->helperData->getActiveSliders();
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
        $bannerCollection = $this->getBannerCollection();

        $bannerList = array();
        foreach ($bannerCollection as $banner) {
            $bannerList[$banner->getSliderId()] = __($banner->getName());
        }

        return $bannerList;
    }

}
