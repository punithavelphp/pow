<?php

namespace Mtwo\Mobile\Model;

use Mtwo\Mobile\Api\ProductBlockInterface;
use Magento\Framework\View\Element\BlockFactory;
use Mageplaza\Productslider\Block\Widget\Slider;
use Mageplaza\BannerSlider\Helper\Data as BannerSliderHelper;
use Mtwo\Mobile\Model\Config as MtwoConfig;
use Mageplaza\BannerSlider\Model\BannerFactory as BannerFactory;

class ProductBlock implements ProductBlockInterface {

    protected $bannerFactory;
    protected $slider;
    protected $mtwoConfig;

    public function __construct(
    Slider $slider
    , BannerSliderHelper $bannerSliderHelper
    , MtwoConfig $mtwoConfig
    , BannerFactory $bannerFactory
    ) {
        $this->slider = $slider;
        $this->bannerSliderHelper = $bannerSliderHelper;
        $this->bannerFactory = $bannerFactory;
        $this->mtwoConfig = $mtwoConfig;
    }

    public function getProductBlocks($blockId = 1) { 
		
		
        $tabName = $this->mtwoConfig->getHomePageSliderValues('mobile_tabs/home_config/tabname' . $blockId);
        $viewmore_link = $this->mtwoConfig->getHomePageSliderValues('mobile_tabs/home_config/tablink' . $blockId);
        $homeBlockSliderId = $this->mtwoConfig->getHomePageSliderValues('mobile_tabs/home_config/productslider' . $blockId);
        $items = array();
        $tab1ProductType = $this->mtwoConfig->getSliderProductType($homeBlockSliderId);
        $collection = $this->slider->setSliderId($homeBlockSliderId)->setProductType($tab1ProductType)->getProductCollection();
        foreach ($collection as $col) {
            //   $col->setData('image',$col->getData('image'))
            $items[] = $col->getData();
        }
        return array(array('name' => $tabName, 'viewmore_link' => $viewmore_link, 'viewmore_link_type' => 'category', 'items' => $items));
    }

    public function getMobileBannerSlider($blockId = 1) {
        $homeBlockSliderId = $this->mtwoConfig->getHomePageSliderValues('mobile_tabs/home_config/banner'.$blockId);
        $items = array();
        $collection = $this->bannerSliderHelper->getBannerCollection($homeBlockSliderId);
        foreach ($collection as $col) {
            //   $col->setData('image',$col->getData('image'))
            $items[] = $col->getData();
        }
        return $items;
    }

    public function getMobileBanner($blockId = 1) {
        $homeBlockBannerId = $this->mtwoConfig->getHomePageSliderValues('mobile_tabs/home_config/banner' . $blockId);
        $bannerData = $this->bannerFactory->create()->load($homeBlockBannerId);
        return array($bannerData->getData());
    }

}
