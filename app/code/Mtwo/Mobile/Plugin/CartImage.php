<?php

namespace Mtwo\Mobile\Plugin;

use Magento\Quote\Api\Data\TotalsItemExtensionInterface;
use Magento\Quote\Api\Data\TotalsItemInterface;
use Magento\Quote\Api\Data\TotalsItemExtensionFactory;
use Magento\Setup\Exception;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Model\Quote\Item;


class CartImage
{
    protected $totalsItemExtensionFactory;
    protected $totalsItemInterface;
    protected $totalsItemExtensionInterface;


    public function __construct(
        TotalsItemInterface $totalsItemInterface,
        TotalsItemExtensionFactory $totalsItemExtensionFactory,
        Item $quoteItem,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productInterfaceFactory)
    {
        $this->totalsItemExtensionFactory = $totalsItemExtensionFactory;
        $this->totalsItemInterface = $totalsItemInterface;
        $this->productFactory = $productFactory;
        $this->quoteItem = $quoteItem;
        $this->stockItem = $stockItem;
        $this->stockRegistry = $stockRegistry;
        $this->productInterfaceFactory = $productInterfaceFactory;

    }


    public function aroundGet(\Magento\Quote\Model\Cart\CartTotalRepository $cartTotalRepository, \Closure $proceed, $cartId)
    {
        $result = $proceed($cartId);

                $message = 1;
        foreach ($result->getItems() as $itemValues) {
            $sku = $this->quoteItem->load($itemValues->getItemId())->getSku();
            $product = $this->productFactory->create();
            $productData = $product->load($product->getIdBySku($sku));
            $setImage = array();
            $productDataObject = $this->productInterfaceFactory->create();
            $productDataObject->setMediaGalleryEntries($productData->getMediaGalleryEntries());
            $productDataObject->setSku($productData->getSku());
            $setImage[] = $productDataObject;
           /* $stockItem = $productData->getExtensionAttributes()->getStockItem();
            $qty = $stockItem->getQty();
            $stock = $this->stockRegistry->getStockItemBySku($productData->getSku())->getIsInStock();
          
             if ($stock) {
                $message = 1;
            } else {
                $message = 0;
            }*/
            $totalItemsConfigExtension = $this->totalsItemExtensionFactory->create();
            $totalItemsConfigExtension->setImage($setImage);
           // $totalItemsConfigExtension->setCustomStock($message);
           // $totalItemsConfigExtension->setCustomQty($qty);
            $itemValues->setExtensionAttributes($totalItemsConfigExtension);
        }
        return $result;
    }

}
