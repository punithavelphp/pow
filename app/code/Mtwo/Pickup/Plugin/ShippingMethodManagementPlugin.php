<?php
namespace Mtwo\Pickup\Plugin;

class ShippingMethodManagementPlugin
{
    public function afterEstimateByExtendedAddress(\Magento\Quote\Model\ShippingMethodManagement $subject,$result,$cartId, \Magento\Quote\Api\Data\AddressInterface $address)
    { 
		//echo $address->getData('extension_attributes')->getAddresscategory();
		$onlyPickup = array();
		if(!empty($address->getData('extension_attributes')->getAddresscategory()) && $result){
			 foreach($result as $res){
 				  if($res->getCarrierCode() == 'storepickup'){
					  $onlyPickup[] = 	 $res;
  				      return $onlyPickup; 
				 } 
			 }
		}else if($result){
			 foreach($result as $res){
 				  if($res->getCarrierCode() != 'storepickup'){
					  $onlyPickup[] = 	 $res;
 				 } 
			 }
			return $onlyPickup;
		}
		 
		return $result;
		
     }
	public function afterEstimateByAddressId(\Magento\Quote\Model\ShippingMethodManagement $subject,$result,$cartId, $addressId)
    { 
		$onlyPickup = array();
	  if($result){
			 foreach($result as $res){
 				  if($res->getCarrierCode() != 'storepickup'){
					  $onlyPickup[] = 	 $res;
 				 } 
			 }
			return $onlyPickup;
		}
		 
		return $result;
		
     }
}
