<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Controller\Result;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Plumrocket\ShippingTracking\Helper\Config;
use Plumrocket\ShippingTracking\Model\AbstractService;

class Index extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Plumrocket\ShippingTracking\Model\AbstractService
     */
    private $trackingModel;

    /**
     * @var \Plumrocket\ShippingTracking\Helper\Config
     */
    private $config;

    /**
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Plumrocket\ShippingTracking\Model\AbstractService $trackingModel
     * @param \Plumrocket\ShippingTracking\Helper\Config         $config
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AbstractService $trackingModel,
        Config $config
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->trackingModel = $trackingModel;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (! $this->config->isModuleEnabled()) {
            return $this->resultRedirectFactory->create()->setPath('404notfound');
        }

        $resultPage = $this->resultPageFactory->create();
        $data = $this->getRequest()->getParams();

        if (!empty($data)) {
            $carrier = key($data);
            if (isset($data[$carrier])) {
                $trackingNumber = $data[$carrier];
                $resultPage->getLayout()->getBlock('pr_shippingtracking_result')
                    ->setData([
                        'carrier' => $carrier,
                        'tracking_number' => $trackingNumber,
                        'order_ids' => $this->trackingModel->getOrderIdsByTrackingNumber($trackingNumber)
                    ]);
            }
        }

        return $resultPage;
    }
}
