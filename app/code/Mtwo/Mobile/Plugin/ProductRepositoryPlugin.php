<?php
 
namespace Mtwo\Mobile\Plugin;

use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\ConfigurableProduct\Api\OptionRepositoryInterfaceFactory;
use Magento\ConfigurableProduct\Api\Data\OptionInterfaceFactory;
use Magento\ConfigurableProduct\Api\Data\OptionValueInterfaceFactory;
use Magento\ConfigurableProduct\Api\Data\OptionValueExtensionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Model\Spi\StockRegistryProviderInterface;
use Webkul\Marketplace\Helper\Data as MarketplaceHelper;
use Webkul\MpAssignProduct\Helper\Data as MpAssignProductHelper;
use Magento\Framework\App\State;
/**
 * Class ProductRepositoryPlugin
 *
 * @package Emc\App\Plugin
 */
class ProductRepositoryPlugin
{
    /**
     * @var AttributeOptionInterfaceFactory
     */
    protected $attributeOptionInterfaceFactory;

    /**
     * @var OptionRepositoryInterfaceFactory
     */
    protected $optionRepositoryInterfaceFactory;

    /**
     * @var OptionInterfaceFactory
     */
    protected $optionInterfaceFactory;

    /**
     * @var OptionValueInterfaceFactory
     */
    protected $optionValueInterfaceFactory;

    /**
     * @var OptionValueExtensionFactory
     */
    protected $optionValueExtensionFactory;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var Configurable
     */
    protected $catalogProductTypeConfigurable;

    /**
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ProductExtensionInterfaceFactory
     */
    protected $extensionFactory;

    /**
     * ProductRepositoryPlugin constructor.
     *
     * @param AttributeOptionInterfaceFactory $attributeOptionInterfaceFactory
     * @param OptionRepositoryInterfaceFactory $optionRepositoryInterfaceFactory
     * @param OptionInterfaceFactory $optionInterfaceFactory
     * @param OptionValueInterfaceFactory $optionValueInterfaceFactory
     * @param OptionValueExtensionFactory $optionValueExtensionFactory
     * @param ResourceConnection $resourceConnection
     * @param Configurable $catalogProductTypeConfigurable
     * @param ProductCollection $productCollection
     * @param StoreManagerInterface $storeManager
     * @param ProductExtensionInterfaceFactory $extensionFactory
     */
    public function __construct(
        StockConfigurationInterface $stockConfiguration,
        StockRegistryProviderInterface $stockRegistryProvider,
        AttributeOptionInterfaceFactory $attributeOptionInterfaceFactory,
        OptionRepositoryInterfaceFactory $optionRepositoryInterfaceFactory,
        OptionInterfaceFactory $optionInterfaceFactory,
        OptionValueInterfaceFactory $optionValueInterfaceFactory,
        OptionValueExtensionFactory $optionValueExtensionFactory,
        ResourceConnection $resourceConnection,
        Configurable $catalogProductTypeConfigurable,
        ProductCollection $productCollection,
        StoreManagerInterface $storeManager,
        ProductExtensionInterfaceFactory $extensionFactory,
        ProductFactory $productFactory,
            MarketplaceHelper $marketplaceHelper,
		MpAssignProductHelper $mpAssignProductHelper,
		State $state
    )
    {
        $this->stockConfiguration = $stockConfiguration;
        $this->stockRegistryProvider = $stockRegistryProvider;
        $this->attributeOptionInterfaceFactory = $attributeOptionInterfaceFactory;
        $this->optionRepositoryInterfaceFactory = $optionRepositoryInterfaceFactory;
        $this->optionInterfaceFactory = $optionInterfaceFactory;
        $this->optionValueInterfaceFactory = $optionValueInterfaceFactory;
        $this->optionValueExtensionFactory = $optionValueExtensionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->productCollection = $productCollection;
        $this->storeManager = $storeManager;
        $this->extensionFactory = $extensionFactory;
        $this->productFactory = $productFactory;
                $this->marketplaceHelper = $marketplaceHelper;
$this->mpAssignProductHelper = $mpAssignProductHelper;
		$this->state = $state;
		$this->code =   $this->state->getAreaCode();

    }
	
	public function afterGet(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
       $product
    ){
		
		 
		 
		if($this->code !='webapi_rest'){
			 return $product;
		}
		 $extensionAttributes = $product->getExtensionAttributes();
		$sellerData = $this->marketplaceHelper->getSellerDataByProductId($product->getId());
            if($sellerData){ 
				$sellerDataArray = array('seller_id'=>$sellerData->getData('seller_id'),'shop_title'=>$sellerData->getData('shop_title'));
                $extensionAttributes->setSellerId($sellerData->getData('seller_id'));
				$extensionAttributes->setShopTitle($sellerData->getData('shop_title'));
				$extensionAttributes->setSellerData(array($sellerData->getData()));
            }
		
			$assignedProducts = $this->mpAssignProductHelper->getAssignProductCollection($product->getId());
		$assignedData  =array();
		if($assignedProducts){ 
				 foreach($assignedProducts as $assignedProduct){
					$assignedData[] = $assignedProduct->getData();
				 }
			 	$extensionAttributes->setAssignedSellerData(($assignedData));
            }
		
		            $product->setExtensionAttributes($extensionAttributes);

		 return $product;
		   
	}

    /**
     * Plugin To add the options to the Search Result
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Magento\Catalog\Api\Data\ProductSearchResultsInterface $productSearchResults
     */
    public function afterGetList(
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Framework\Api\SearchResults $searchCriteria,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriterias
    )
    {
		
		if($this->code == 'webapi_rest'){
			 return $searchCriteria;
		}
        $items = $searchCriteria->getItems();
        $activeFields = false;
        $mainConfig = [];
        $simpleIds = [];
        $filterArray = [];
       $scopeId = null;
       
        foreach ($items as $item) {
            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            if ($item->getTypeId() == 'configurable') {
                $productTypeInstance = $item->getTypeInstance();
                //start of super global configurable attributes
                $dataConfigurable = $productTypeInstance->getConfigurableOptions($item);
                $optionCount = count($dataConfigurable);
                if ($optionCount == 1) {
                    $optionsSkuPrice = array();
                    foreach ($productTypeInstance->getUsedProducts($item) as $child) {

                        $optionsSkuPrice[$child->getData('sku')] = $child->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
                    }
                    $optionsSku = array();
                    foreach ($dataConfigurable as $dataConfigurableattr) {

                        foreach ($dataConfigurableattr as $dataConfigurablekey) {
                            $optionsSku[$dataConfigurablekey['value_index']] = $dataConfigurablekey['sku'];
                        }
                    }
                }
                $optionRepository = $this->optionRepositoryInterfaceFactory->create();
                $options = $optionRepository->getList($item->getSku());
                $resConnection = $this->resourceConnection->getConnection();
                $eavTableName = $this->resourceConnection->getTableName('eav_attribute_option_value'); //gives table name with prefix
               /* foreach ($options as $option) {
                    $optionValues = [];
                    foreach ($option->getValues() as $optionValue) {
                        $eavSql = "Select * FROM " . $eavTableName . " where option_id = " . $optionValue->getValueIndex() . " AND store_id = " . $this->storeManager->getStore()->getId();
                        $eavResult = $resConnection->fetchAll($eavSql);
                        if (count($eavResult) == 0) {
                            $eavSql = "Select * FROM " . $eavTableName . " where option_id = " . $optionValue->getValueIndex();
                            $eavResult = $resConnection->fetchAll($eavSql);
                        }
                        $optionExtension = $this->optionValueExtensionFactory->create();
                        $optionExtension->setValueLabel($eavResult[0]['value']);
                        if ($optionCount == 1) {
                            $optionExtension->setValueSku($optionsSku[$optionValue->getValueIndex()]);
                            if(array_key_exists($optionsSku[$optionValue->getValueIndex()],$optionsSkuPrice)){
                            $optionExtension->setValuePrice($optionsSkuPrice[$optionsSku[$optionValue->getValueIndex()]]);}

                            $product = $this->productFactory->create();
                            $productInfo=$product->loadByAttribute('sku', $optionsSku[$optionValue->getValueIndex()]);
                            $productPrice = $productInfo->getPrice();
                            $optionExtension->setActualPrice($productPrice);
                            $optionExtension->setSimpleProductImage($product->load($productInfo->getEntityId())->getMediaGalleryEntries());

                        }
                        $optionValue->setExtensionAttributes($optionExtension);
                        $optionValues[] = $optionValue;
                    }
                    $option->setValues($optionValues);
                } */

                $extensionAttributes = $item->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $extensionAttributes->setOptions($options);
                $extensionAttributes->setCustomFinalPrice($item->getPriceInfo()->getPrice('final_price')->getAmount()->getValue());
                $extensionAttributes->setCustomStockItem($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getStockItem());
                $extensionAttributes->setCustomStockStatus($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getStockStatus());
                $extensionAttributes->setCustomStockQty($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getQty());
                $extensionAttributes->setOriginalFinalPrice($item->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue());
            } else {
                $extensionAttributes = $item->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $extensionAttributes->setCustomFinalPrice($item->getPriceInfo()->getPrice('final_price')->getAmount()->getValue());
                $extensionAttributes->setCustomStockItem($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getStockItem());
                $extensionAttributes->setCustomStockStatus($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getStockStatus());
                $extensionAttributes->setCustomStockQty($this->stockRegistryProvider->getStockStatus($item->getId(), $scopeId)->getQty());
                $extensionAttributes->setOriginalFinalPrice($item->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue());
            }
            $sellerData = $this->marketplaceHelper->getSellerDataByProductId($item->getId());
            if($sellerData){ 
				$sellerDataArray = array('seller_id'=>$sellerData->getData('seller_id'),'shop_title'=>$sellerData->getData('shop_title'));
                $extensionAttributes->setSellerId($sellerData->getData('seller_id'));
				$extensionAttributes->setShopTitle($sellerData->getData('shop_title'));
				$extensionAttributes->setSellerData(array($sellerData->getData()));
            }
			$assignedProducts = $this->mpAssignProductHelper->getAssignProductCollection($item->getId());
		if($assignedProducts){ 
			$assignedData = [];
				 foreach($assignedProducts as $assignedProduct){
					$assignedData[] = $assignedProduct->getData();
				 }
			 	$extensionAttributes->setAssignedSellerData(($assignedData));
            }
            $item->setExtensionAttributes($extensionAttributes);
        }
        return $searchCriteria;
    }
}
