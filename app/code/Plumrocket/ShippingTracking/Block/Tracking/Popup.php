<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Block\Tracking;

class Popup extends \Magento\Shipping\Block\Tracking\Popup
{
    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    private $trackingResultFactory;

    /**
     * @var \Plumrocket\ShippingTracking\Helper\Config
     */
    private $config;

    /**
     * @param \Plumrocket\ShippingTracking\Helper\Config                    $config
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory         $trackingResultFactory
     * @param \Magento\Framework\View\Element\Template\Context              $context
     * @param \Magento\Framework\Registry                                   $registry
     * @param \Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface $dateTimeFormatter
     * @param array                                                         $data
     */
    public function __construct(
        \Plumrocket\ShippingTracking\Helper\Config $config,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackingResultFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface $dateTimeFormatter,
        array $data = []
    ) {
        $this->trackingResultFactory = $trackingResultFactory;
        $this->config = $config;

        parent::__construct($context, $registry, $dateTimeFormatter, $data);
    }

    /**
     * Retrieve array of tracking info
     *
     * @return array
     */
    public function getTrackingInfo()
    {
        $results = parent::getTrackingInfo();

        if (!$this->config->isModuleEnabled()) {
            return $results;
        }

        foreach ($results as $shipping => $result) {
            foreach ($result as $key => $track) {
                if (!is_object($track)) {
                    continue;
                }

                $carrier = $track->getCarrier();

                if ($this->config->getEnabledMethodByName($carrier)) {
                    $url = $this->getUrl('shippingtracking/result/index', [$carrier => trim($track->getTracking())]);
                    $results[$shipping][$key] = $this->trackingResultFactory->create()->setData($track->getAllData())
                        ->setErrorMessage(null)
                        ->setUrl($url);
                }
            }
        }

        return $results;
    }
}
