<?php
namespace Mstore\OrdersList\Model;
use Mstore\OrdersList\Api\OrdersListInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;

class OrdersListRepository implements OrdersListInterface
{
    public function __construct(
		\Magento\Sales\Model\OrderFactory $orderFactory
		, ObjectManager $ObjectManager
		, \Magento\Framework\App\Response\Http\FileFactory $fileFactory
) {
        $this->orderFactory = $orderFactory;
		        $this->_objectManager = $ObjectManager;
		        $this->fileFactory = $fileFactory;

    }
        
    public function getAllOrders()
    {
	 $invoiceId = 5;

       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 //	$fileFactory = $objectManager->get('Magento\Framework\App\Response\Http\FileFactory');
  		   //  $invoice = $objectManager->get('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
	 	 // $pdf = $objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
           // $date = $objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime')->date('Y-m-d_H-i-s');
           /*  $fileFactory->create(
                'invoice' . $date . '.pdf',
                $pdf->render(),
                \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                'application/pdf'
            );   */
		//return   'invoice' . $date . '.pdf';
 
    }
	public function getAllOrdersOLD($page)
    {
        $orderCollecion = $this->orderCollectionFactory->create()->addFieldToSelect('*');
		 
         return $orderCollecion->getData();
    }
}