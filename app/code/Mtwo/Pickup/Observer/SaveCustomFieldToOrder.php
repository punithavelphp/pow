<?php

namespace Mtwo\Pickup\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveCustomFieldToOrder implements ObserverInterface {

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectmanager) {
        $this->_objectManager = $objectmanager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $quoteShippingAdress = $observer->getQuote()->getShippingAddress();
		 

		
        $orderShippingAdress = $observer->getOrder()->getShippingAddress();
         $orderShippingAdress->setAddresscategory($quoteShippingAdress->getAddresscategory());
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $shipSameAsBilling = $quoteShippingAdress->getSameAsBilling();

        if ($shipSameAsBilling) {
            $orderBillingAdress = $observer->getOrder()->getBillingAddress();
           $orderBillingAdress->setAddresscategory($quoteShippingAdress->setAddresscategory());
 
            $quoteBillingAdress = $observer->getQuote()->getBillingAddress();
          $quoteBillingAdress->setAddresscategory($quoteShippingAdress->getAddresscategory());
         }

        $addressRepository = $objectManager->create('\Magento\Customer\Api\AddressRepositoryInterface');
        $addressObject = $addressRepository->getById($quoteShippingAdress->getCustomerAddressId());
     $addressObject->setCustomAttribute('addresscategory', $quoteShippingAdress->getAddresscategory());
         $addressRepository->save($addressObject);
        return $this;
    }
	 

}
