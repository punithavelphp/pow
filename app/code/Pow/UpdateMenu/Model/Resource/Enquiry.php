<?php
 
namespace Pow\UpdateMenu\Model\Resource;
 
use \Magento\Framework\Model\Resource\Db\AbstractDb;
 
class Enquiry extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('updatemenu', 'id');
    }
}