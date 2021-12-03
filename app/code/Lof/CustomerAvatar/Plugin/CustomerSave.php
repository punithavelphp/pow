<?php
 namespace Lof\CustomerAvatar\Plugin;
 
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Api\Data\CustomerInterface;


class CustomerSave {

public function beforeSave(CustomerRepository $subject, CustomerInterface $customer)
{
		   $avatar = $customer->getCustomAttribute('avatar');
	 
		    if($avatar != ''){
				$avatar = $customer->getCustomAttribute('avatar')->getValue();
				$customerId = $customer->getId();
				$image = $this->setProfileImage($customerId,$avatar,$customer->getFirstname());
			    $customer->setCustomAttribute('profile_picture',$image);
			}
		   
		   
 }
	
public function setProfileImage($customerId, $base64, $name){ 
        if ($customerId) {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $fileSystem = $objectManager->create('\Magento\Framework\Filesystem');
            $mediaPath = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
            $media = $mediaPath . 'customer/';
             $data = $base64;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
			
			 switch (strtolower($type)) {
                case 'data:image/gif':
                    $imageExtn = 'gif';
                    break;
                case 'data:image/jpeg':
                    $imageExtn = 'jpg';
                    break;
                case 'data:image/png':
                    $imageExtn = 'png';
                    break;
                default:
                    $imageExtn = 'jpeg';
                    break;
            }
			
			$unique_id = '/'.strtolower($name) .'-'. uniqid();
             if (file_put_contents($media . $unique_id . '.'.$imageExtn, $data)) {
                return $unique_id.'.'.$imageExtn;
            } else {
                return '';
            }
        }
    }



    }
