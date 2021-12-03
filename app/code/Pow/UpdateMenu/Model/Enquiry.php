<?php
 
namespace Pow\UpdateMenu\Model;
 
use \Magento\Framework\Model\AbstractModel;
 
class Enquiry extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */

    protected function _construct()
    {
        $this->_init('Pow\UpdateMenu\Model\Resource\Enquiry');

    }
    public function addAttributeUpdate($code, $value, $store)
   {
   
    }
}
