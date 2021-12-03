<?php

namespace Mtwo\ProductAttributes\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action {

    protected $resultPageFactory = false;

    /**
     * Initialize
     *
     * @param Action\Context $context           Initialize Context
     * @param PageFactory    $resultPageFactory Initialize resultPageFactory
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Return Title
     *
     * @return Careers
     */
    public function execute() {


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->create('\Mtwo\ProductAttributes\Helper\Data');
 
        $attributeCode = 'liter';
         $labelArray[$attributeCode] = array('750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','750ML','500 GR','300 GR','1 KG','1 KG','200 GR','200 GR','5 KG','110 GR','500 ML','500 ML','18.9 OZ','12 OZ','12 OZ','12 OZ','500 ML','7 LB','200 GR','500 ML','7 OZ','UNIT','UNIT','55 GR','150 GR','UNIT','UNIT','UNIT','55 GR','500 ML','250 ML','UNIT','375 GR','150 GR','500 ML','UINT','7 OZ','UNIT','500 ML','UNIT','225 GR','660 gr','2.85 LB','1 KG','UNIT','500 ML','UNIT','300 GR','400GR','1 KG','18.9 OZ','400 GR','520 GR','UNIT','500 ML','750ML','UNIT','UNIT','UNIT','500 GR','550 GR','UNIT','550 GR','11.6 OZ','1.13 LB','BIG','6 OZ','6 OZ','6 OZ','120 GR','12 GR','180 GR','300 GR','245GR','120 GR','112 GR','220 GR','390 GR','1000GR','80GR','260 GR','260 GR','260 GR','30GR','30GR','200 GR','120 GR','250 GR','250 GR','250 GR','250 GR','250 GR','250 GR','250 GR','125 GR','120 GR','120 GR','125 GR','115 GR','125 GR','115 GR','125 GR','1 KG','500 GR','200 GR','250 ML','375 ML','500 GR','180 GR','70 GR','180 GR','70 GR','22.9 OZ','1.8 OZ','8.5 OZ','UNIT','12 OZ','1 LT','520 GR','UNIT','UNIT','250 GR','200 GR','6 OZ','200 GR','1 LT','500 GR','UNIT','UNIT','25 GR','160 GR','UNIT','266GR','250 GR','250 GR','4OZ','180 GR','150 GR','330 ML','UNIT','1.5 OZ','UNIT','UNIT','330 ML','200 GR','200 GR','18.9 OZ','UNIT','200 G','500 GR','225 GR','9 OZ','UNIT','UNIT','280 GR','280 GR','300 GR','160 GR','20 GR','72 GR','300 GR','500 GR','250 GR','UNIT','UNIT','UNIT','UNIT','UNIT','UNIT','180 GR','500GR','UNIT','UNIT','3.5 OZ','7.9 OZ','500GR','126 GR','12.5 OZ','12.5 OZ','2 LB','UNIT','7.9 OZ','UNIT','3 LB 11/15','3 LB 8/10','16 OZ','UNIT','133 GR','133 GR','6 PACK','6 PACK','180 GR','UNIT','UNIT','UNIT','UNIT','UNIT','UNIT','800 GR','240 GR','500 GR','500 GR','200 ML','250 GR','9.8 OZ','7 OZ','10 OZ','57 GR','11 OZ','340 GR','1 GR','57 GR','1 KG','UNIT','400 GR','20 GR','200 GR','4OZ','111 GR','UNIT','65 GR','800 GR','UNIT','1 KG','500 ML','1 KG','UNIT','UNIT','UNIT','390 GR','125 GR','3 OZ','UNIT','330 GR','UNIT','1 LT','UNIT','450 GR','200 GR','200 GR','500 ML','800 GR','75 GR','65 GR','6 OZ','CASE','6 PACK','500 GR','UNIT','75 GR','500 GR','280 GR','UNIT','385 GR','UNIT','240 GR','4.2 OZ','UNIT','400 GR','6 PACK','6 PACK','3PACK','350 GR','7.9 OZ','180 GR','1 LT','3 OZ','45 GR','330 ML','200GR','100 GR','100 GR','UNIT','1.5 LT','1.5 LT','800 GR','UNIT','UNIT','UNIT','UNIT','250 GR','180 GR','340 GR','UNIT','750ML','UNIT','7 OZ','32 OZ','3.5 OZ','750ML','150 GR','20 GR','30GR','350 GR','UNIT','UNIT','200 GR','12.2 OZ','210 GR','UNIT','UNIT','2.2 LB','5 OZ','UNIT','1.5 LT','1.5 LT','30 GR','UNIT','200 GR','1 LT','330 ML','45 GR','45 GR','120 GR','45 GR','45 GR','7 OZ','20 GR','20 GR','20 GR','45 GR','45 GR','CASE','300 ML','20 GR','120 GR','CASE','300 ML','UNIT','45 GR','UNIT');

 $labelArray[$attributeCode] = array_unique($labelArray[$attributeCode]);
		 
 echo '<pre>----->' . __FILE__ . '--->' . __LINE__ . '--->';
                    print_r($labelArray[$attributeCode]);
                    echo '</pre>'; 
        foreach ($labelArray as $attributeCode => $labelArr) {
                 foreach ($labelArr as $label) {
                    $id = $helper->createOrGetId($attributeCode, $label);
                    echo '<pre>----->' . __FILE__ . '--->' . __LINE__ . '--->';
                    print_r($label.'--'.$id);
                    echo '</pre>';
             }
        }
        die;
    }

}