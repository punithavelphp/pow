<?php

namespace Mtwo\Configurations\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper {
 
    protected $messageManager;
    protected $categoryRepository;

    public function __construct(
    	\Magento\Framework\App\Helper\Context $context
		,\Magento\Store\Model\StoreManagerInterface $storeManager
		, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
   		,\Magento\Framework\Message\ManagerInterface $messageManager
		,        \Magento\Catalog\Model\CategoryRepository $categoryRepository

    ) {
        $this->_storeManager = $storeManager;
		$this->_scopeConfig = $scopeConfig;
         $this->brandProductsTab = array();
         $this->messageManager = $messageManager;
        $this->categoryRepository = $categoryRepository;

        parent::__construct($context);
    }
 

    public function setProductTab($product) {
        if ($product->getProductBrand() != '') {
            $this->brandProductsTab[$product->getProductBrand()] = $product->getProductBrand();
        }
    }

    public function getProductTab() {
        return $this->brandProductsTab;
    }

    public function setProductTabNull() {
        $this->brandProductsTab = array();
    }
 
  
    public function getHomePageSliderValues($key) {
        return $this->_scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getSliderProductType($sliderId) {
        $productType = 'custom';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $sliderFactory = $objectManager->create('Mageplaza\Productslider\Model\Slider');
        $sliderCollection = $sliderFactory->getCollection()->addFieldToFilter('slider_id', $sliderId);
        foreach ($sliderCollection as $sliderDetails) {
            $productType = $sliderDetails->getProductType();
        }
        return $productType;
    } 
	
	 public  function getCategory($categoryId)
    {
          return  $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());

    }
	 public  function getCategoryUrl($categoryId)
    {
            $category = $this->getCategory($categoryId);

        return $category->getUrl();
    }
 
     

}
