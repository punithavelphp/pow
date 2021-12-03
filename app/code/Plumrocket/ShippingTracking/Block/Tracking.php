<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Block;

use Magento\Framework\View\Element\Template;

class Tracking extends Template
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \Plumrocket\ShippingTracking\Helper\Config
     */
    private $config;

    /**
     * @var \Plumrocket\ShippingTracking\Model\ServiceManager
     */
    private $serviceManager;

    /**
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface       $orderRepository
     * @param \Plumrocket\ShippingTracking\Helper\Config        $config
     * @param \Plumrocket\ShippingTracking\Model\ServiceManager $serviceManager
     * @param array                                             $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Plumrocket\ShippingTracking\Helper\Config $config,
        \Plumrocket\ShippingTracking\Model\ServiceManager $serviceManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderRepository = $orderRepository;
        $this->config = $config;
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param $orderId
     * @return null|string
     */
    public function getOrderStatus($orderId = null)
    {
        if (empty($orderId)) {
            $orderId = $this->getOrderId();
        }

        return $this->orderRepository->get($orderId)->getState();
    }

    /**
     * @return mixed
     */
    public function getTrackingInfo()
    {
        $data = $this->getResultData();
        $carrier = $data['carrier'];
        $result = [];
        $availableServices = array_keys($this->getAvailableServices());

        if (in_array($carrier, $availableServices) && !empty($data['tracking_number'])) {
            $serviceModel = $this->serviceManager->getServiceByName($carrier);
            $result = $serviceModel->getTrackingInfo($data['tracking_number']);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAvailableServices()
    {
        $availableServices = [];

        if ($this->config->enabledUpsApi()) {
            $availableServices['ups'] = __('UPS');
        }

        if ($this->config->enabledFedexApi()) {
            $availableServices['fedex'] = __('FedEx');
        }

        if ($this->config->enabledUspsApi()) {
            $availableServices['usps'] = __('USPS');
        }

        return $availableServices;
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->getUrl("shippingtracking/search/index");
    }

    /**
     * @return mixed
     */
    public function getResultData()
    {
        return $this->getData();
    }

    /**
     * @return int|mixed
     */
    public function getOrderId()
    {
        $data = $this->getResultData();
        return isset($data['order_ids']) ? array_shift($data['order_ids']) : 0;
    }

    /**
     * @return null|string
     */
    public function getOrderIncrementId()
    {
        return $this->orderRepository->get($this->getOrderId())->getIncrementId();
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->getData('carrier');
    }

    /**
     * @return bool
     */
    public function getServiceIcon()
    {
        $carrier = $this->getServiceName();

        return $this->getViewFileUrl(
            'Plumrocket_ShippingTracking::images/icons/' . $carrier . '.png'
        );
    }

    /**
     * @return mixed
     */
    public function getTrackingNumber()
    {
        return $this->getData('tracking_number');
    }

    /**
     * @return bool
     */
    public function canShow()
    {
        $data = $this->getResultData();
        if (empty($data['carrier'])
            || empty($data['tracking_number'])
            || empty($data['order_ids'])
        ) {
            return false;
        }

        return true;
    }
}
