<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2019 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Controller\Search;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Plumrocket\ShippingTracking\Helper\Config;
use Plumrocket\ShippingTracking\Model\AbstractService;

class Index extends Action
{
    /**
     * Base Url
     */
    const BASE_URL = 'shippingtracking/index/index';

    /**
     * Result Url
     */
    const RESULT_URL = 'shippingtracking/result/index';

    /**
     * @var \Plumrocket\ShippingTracking\Model\AbstractService
     */
    private $trackingModel;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Plumrocket\ShippingTracking\Helper\Config
     */
    private $config;

    /**
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Plumrocket\ShippingTracking\Model\AbstractService $trackingModel
     * @param \Plumrocket\ShippingTracking\Helper\Config         $config
     */
    public function __construct(
        Context $context,
        AbstractService $trackingModel,
        Config $config
    ) {
        parent::__construct($context);

        $this->messageManager = $context->getMessageManager();
        $this->trackingModel = $trackingModel;
        $this->config = $config;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (! $this->config->isModuleEnabled()) {
            return $resultRedirect->setPath('404notfound');
        }

        $data = $this->getRequest()->getParams();

        if (isset($data['shippingtracking']['order'], $data['shippingtracking']['info'])) {
            $orderId = trim($data['shippingtracking']['order']);
            $info = trim($data['shippingtracking']['info']);
            $data = $this->trackingModel->getTrackingNumberByOrderData($orderId, $info);
            $params = [];

            if (!empty($data)) {
                $carrier = key($data);

                if (isset($data[$carrier])) {
                    $params[$carrier] = $data[$carrier];
                }
            }

            if (!empty($params)) {
                return $resultRedirect->setPath(self::RESULT_URL, $params);
            }

            $this->messageManager->addErrorMessage(
                __('Make sure that you have entered the Order Number and phone number (or email address) correctly.')
            );
        } elseif (isset($data['number'], $data['carrier'])) {
            return $resultRedirect->setPath(self::RESULT_URL, [$data['carrier'] => $data['number']]);
        }

        return $resultRedirect->setPath(self::BASE_URL);
    }
}
