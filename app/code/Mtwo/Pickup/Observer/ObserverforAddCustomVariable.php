<?php
namespace Mtwo\Pickup\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;
class ObserverforAddCustomVariable implements ObserverInterface
{
    protected $customerRepository;
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		
        /** @var \Magento\Framework\App\Action\Action $controller */
        $transport = $observer->getEvent()->getTransport();
        if ($transport->getOrder() != null) 
        {
            $order = $transport->getOrder();
			$transport['pickup'] = 'false';
			$shipping_method = $order->getShippingAddress()->getShippingMethod();
			if(strpos(strtolower($shipping_method),'pickup') !== false){
				$transport['pickup'] = 'true';
			}
        }
		 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$storeInformation = $objectManager->create('Magento\Store\Model\Information');
		$store = $objectManager->create('Magento\Store\Model\Store');

$storeInfo = $storeInformation->getStoreInformationObject($store);
		
		 
		$br = "\r\n";
		$html = $storeInfo->getData('name').$br;
		$html .= $storeInfo->getData('street_line1').",".$br;
		$html .= $storeInfo->getData('street_line2').",".$br;
		$html .= $storeInfo->getData('region').",".$br;
		$html .= $storeInfo->getData('country_id').",".$br;
		$html .= $storeInfo->getData('postcode')." ".$br;
		$html .= "Tel: ". $storeInfo->getData('phone').$br;

		$transport['storeaddress'] =  $html;
		
		
		$helper = $objectManager->create('Solwin\Cpanel\Helper\Data');

		/*--------------*/
		$opening_hours = '';
		 		$opening_hours .= '<h3>Delivery Hours</h3>';
          $opening_hours .= '<ul class="checkout-delivery-hours">';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Monday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day1');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Tuesday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day2');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Wednesday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day3');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Thursday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day4');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Friday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day5');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Saturday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day6');
		$opening_hours .= '</li>';
		$opening_hours .= '<li>';
		$opening_hours .= '<span>Sunday</span>';
		$opening_hours .= '<span>'. $helper->getConfigDeliveryHours('day7');
		$opening_hours .= '</li>';
		$opening_hours .= '</ul>';
		
	 
		$transport['opening_hours'] = $opening_hours;
		 
    }
}