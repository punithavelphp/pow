<?php

namespace Mtwo\Mobile\Plugin\Quote\Item;

use Psr\Log\LoggerInterface;
 use Magento\Quote\Api\CartRepositoryInterface;

class Repository {

    /**
     * @var Psr\Log\LoggerInterface;
     */
    public $logger;
    protected $scopeConfigInterface;
    protected $quoteRepository;
    protected $helper;
    protected $productRepository;

    public function __construct(
    LoggerInterface $logger
    , \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    , CartRepositoryInterface $quoteRepository
        ,\Magento\Quote\Api\Data\CartItemExtensionFactory $cartItemExtension
    , \Webkul\MarketPlace\Helper\Data $helper
    , \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->logger = $logger;
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->cartItemExtension = $cartItemExtension;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
    }

    /**
     * Plugin to add seller id into extension attributes
     * @param \Magento\Quote\Model\Quote\Item\Repository $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Api\Data\CartItemInterface $cartItem
     * @return string
     */
    public function aroundSave(
    \Magento\Quote\Model\Quote\Item\Repository $subject, \Closure $proceed, \Magento\Quote\Api\Data\CartItemInterface $cartItem) {
		return $response = $proceed($cartItem);
        $this->logger->info("aroundSave Plugin Started");

        
            if ($this->addSellerToQuoteItem($cartItem)) {
                $response = $proceed($cartItem);
            } else {
                $this->logger->info("aroundSave Single seller error");
                $response['error'] = array('staus' => false, 'mesage' => __('Your cart contains items from a different Store. Would you like to remove other store products before adding this store product?'));
                $message = 'Your cart contains items from a different Store. Would you like to remove other store products before adding this store product?';

                throw new \Magento\Framework\Webapi\Exception(
                __(
                        $message
                ), 0, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
                
            }
         
        return $response;
    }

    public function addSellerToQuoteItem($cartItem) {
        
    
                 $currentSku = $cartItem->getSku();
                if ($currentSku) {
                    $product = $this->productRepository->get($currentSku);
                    $sellerId = $this->helper->getSellerIdByProductId($cartItem->getProductId());
					$extensionAttributes = $cartItem->getExtensionAttributes();
					if ($extensionAttributes === null) {
						$extensionAttributes = $this->cartItemExtension->create();
					}
					                $extensionAttributes->setSellerId($sellerId);

					$cartItem->setExtensionAttributes($extensionAttributes);

                 
             }
         
        return true;
    }

}
