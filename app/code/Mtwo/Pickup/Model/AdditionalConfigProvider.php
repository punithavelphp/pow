<?php
namespace Mtwo\Pickup\Model;
class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
	
	public function getConfig()
	{
		
 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$storeInformation = $objectManager->create('Magento\Store\Model\Information');
		$store = $objectManager->create('Magento\Store\Model\Store');

$storeInfo = $storeInformation->getStoreInformationObject($store);

		$output['store_info'] = $storeInfo->getData();
		$output['shopAddress']['company'] = $storeInfo->getData('name');
		$output['shopAddress']['city'] = $storeInfo->getData('city');
		$output['shopAddress']['country_id'] = $storeInfo->getData('country_id');
		$output['shopAddress']['country_id'] = $storeInfo->getData('country_id');
		//	$output['shopAddress']['region'] = $storeInfo->getData('region');
		$region_data = array('region_id'=>(int) $storeInfo->getData('region_id'),'region_code'=>'AK','region'=> $storeInfo->getData('region'));;
		$output['shopAddress']['region'] = (object) $region_data;
		// $output['shopAddress']['region']['region_code'] = 'AK';
	 //	$output['shopAddress']['region_id'] =  (int) $storeInfo->getData('region_id');
		//$output['shopAddress']['regionId'] = $storeInfo->getData('region_id');
 		$output['shopAddress']['street'] = array($storeInfo->getData('street_line1'),$storeInfo->getData('street_line2'));
		/*$output['shopAddress']['custom_attributes'] = array(
		array('attribute_value'=>'SHOPID','attribute_code'=>'shopid')
		);*/
 		$output['shopAddress']['firstname'] = $storeInfo->getData('name');
 		$output['shopAddress']['lastname'] = '.';
 		$output['shopAddress']['telephone'] = $storeInfo->getData('phone');
	    $output['shopAddress']['pickup'] = true;
		$output['shopAddress']['postcode'] = $storeInfo->getData('postcode');
		$output['shopAddress']['default_shipping'] = false;
		$output['shopAddress']['default_billing'] = false;
		$output['shopAddress']['save_in_address_book'] = 0;
		$output['shopAddress']['extension_attributes'] = array(
		'addresscategory'=>'STORE PICKUP'
		);
		$output['shopAddress']['extensionAttributes'] = array(
		'addresscategory'=>'PICKUP'
		);
		$output['shopAddress']['custom_attributes'] = array(
		array('attribute_code'=>'addresscategory','value'=>'STORE PICKUP')
		);
		$output['shopAddress']['city'] = $storeInfo->getData('city');
 	/*---------------*/
		
			
		$output['shopAddress']['pickupAddress']["address"] = array(
			"street" =>array(
				$storeInfo->getData('street_line1'),
				$storeInfo->getData('street_line2')
			),
			"city"=>$storeInfo->getData('city'),
			"region_id"=>$storeInfo->getData('region_id'),
			"region"=>$storeInfo->getData('region'),
			"country_id"=>$storeInfo->getData('country_id'), 
			"postcode"=>$storeInfo->getData('postcode'), 
			"firstname"=>$storeInfo->getData('name'),
			"lastname"=>$storeInfo->getData('name'),
			"company"=>$storeInfo->getData('name'), 
			"telephone"=>$storeInfo->getData('phone'), 
			"save_in_address_book"=>0 
		);
				
				
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
		
	 
		$output['opening_hours'] = $opening_hours;
 		return $output;
	}
}