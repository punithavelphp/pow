<?php
namespace Mtwo\Configurations\Model\Config\Source;
use Magento\Framework\Option\ArrayInterface;

class ProductSlider implements ArrayInterface
{
    protected $_productSlider;

    public function __construct(
        \Mageplaza\Productslider\Model\SliderFactory $productSlider
    )
    {
        $this->_productSlider = $productSlider;
    }

    public function getSliderCollection()
    {
        $collection = $this->_productSlider->create()->getCollection()->addFieldToFilter('status', 1);
        return $collection;
    }

    public function toOptionArray()
    {
        $arr = $this->_toArray();
		$ret = [];
        $ret[] = ['value' => '', 'label' => 'Select'];

        foreach ($arr as $key => $value)
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    private function _toArray()
    {
        $sliderCollection = $this->getSliderCollection();

        $sliderList = array();
        foreach ($sliderCollection as $slider)
        {
            $sliderList[$slider->getSliderId()] = __( $slider->getName());
        }

        return $sliderList;
    }
}