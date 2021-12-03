<?php
namespace Pow\UpdateMenu\Controller\Adminhtml\Create;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

class Index extends \Magento\Backend\App\Action {
  protected $resultPageFactory = false;    
  protected $directoryList;
  protected $file;
  protected $_filesystem;
  protected $productRepositoryInterface;


  public function __construct(
   \Magento\Backend\App\Action\Context $context,
   \Magento\Framework\View\Result\PageFactory $resultPageFactory,
   DirectoryList $directoryList,
   \Magento\Framework\Filesystem $filesystem,
   File $file
 ) {
    parent::__construct($context);
    $this->resultPageFactory = $resultPageFactory;
    $this->directoryList = $directoryList;
    $this->_filesystem = $filesystem;
    $this->file = $file;

  } 
  public function execute() {
    $resultPage = $this->resultPageFactory->create();
    $resultPage->setActiveMenu('Pow_UpdateMenu::menu');
    $resultPage->getConfig()->getTitle()->prepend(__('Product Update status'));
    $ch = curl_init();
    $url = "http://werkmen.com/pow_master/rest/all/V1/products?searchCriteria%5BsortOrders%5D%5B0%5D%5Bdirection%5D=ASC&searchCriteria%5BpageSize%5D=20&searchCriteria%5BcurrentPage%5D=1";
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $products = json_decode(curl_exec($ch));
    // var_dump($products->items);die;
    $master_products = $products->items;
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
    $client_collection = $productCollection->addAttributeToSelect('*')
                        ->addAttributeToFilter('status', 1)
                        ->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
                        ->load();
    foreach ($client_collection as $product){
      $collections = $master_products; 
      foreach ($collections as  $key => $master_product) {
        if ($product->getSku()==$master_product->sku) {
          $product = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product');
          
          if(isset($master_product->media_gallery_entries[0])){
            $remoteImage = "http://localhost/pow_master/pub/media/catalog/product/".$master_product->media_gallery_entries[0]->file;
            $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR.'catalog' . DIRECTORY_SEPARATOR.'product' . DIRECTORY_SEPARATOR;
            $this->file->checkAndCreateFolder($tmpDir);
            $newFileName = $tmpDir . 'sample2_img.jpeg';
            $result = $this->file->read($remoteImage, $newFileName);
            if ($result) {
              $product->addImageToMediaGallery('catalog/product/sample2_img.jpeg', 'jpeg', true, false);
            }
            curl_close($ch);  
          }
          $product->setName($master_product->name);
          $product->setAttributeSetId($master_product->attribute_set_id);
          $product->setStatus($master_product->status);
          $product->setVisibility($master_product->visibility);
          $product->setTypeId($master_product->type_id);
          if (isset($master_product->weight)) {
            $product->setWeight($master_product->weight);
          }
          $product->save();
          unset($master_products[$key]);
        }
      }
    }
    if(count($master_products)){
      foreach ($master_products as  $key => $master_product) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product_new = $objectManager->create('Magento\Catalog\Model\Product');
        if(isset($master_product->media_gallery_entries[0])){
          $remoteImage = "http://localhost/pow_master/pub/media/catalog/product/".$master_product->media_gallery_entries[0]->file;
          $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR.'catalog' . DIRECTORY_SEPARATOR.'product' . DIRECTORY_SEPARATOR;
          $this->file->checkAndCreateFolder($tmpDir);
          $newFileName = $tmpDir . 'sample2_img.jpeg';
          $result = $this->file->read($remoteImage, $newFileName);
          if ($result) {
            $product_new->addImageToMediaGallery('catalog/product/sample2_img.jpeg', 'jpeg', true, false);
          }
        }
        $product_new->setName($master_product->name);
        $product_new->setSku($master_product->sku);
        $product_new->setAttributeSetId($master_product->attribute_set_id);
        $product_new->setStatus($master_product->status);
        $product_new->setVisibility($master_product->visibility);
        $product_new->setTypeId($master_product->type_id);
        $product_new->setStockData(
                array(
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'qty' => 999999999
                )
            );
        $product_new->save();
      }
    }
  return $resultPage;
}  

protected function _isAllowed()
{
 return $this->_authorization->isAllowed('Pow_UpdateMenu::menu');
}

}