<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class Data Helper
 * @deprecated since 1.0.6
 * @see \Plumrocket\ShippingTracking\Helper\Config
 */
class Data extends AbstractHelper
{
    /**
     * Config section id
     * @deprecated since 1.0.6
     * @see \Plumrocket\ShippingTracking\Helper\Config::SECTION_ID
     */
    const SECTION_ID = Config::SECTION_ID;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param \Magento\Framework\App\Helper\Context      $context
     * @param \Plumrocket\ShippingTracking\Helper\Config $config
     */
    public function __construct(
        Context $context,
        Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
    }

    /**
     * @deprecated since 1.0.6
     * @see \Plumrocket\ShippingTracking\Helper\Config::isModuleEnabled()
     *
     * @param null $store
     * @return bool
     */
    public function moduleEnabled($store = null)
    {
        return $this->config->isModuleEnabled($store);
    }

    /**
     * @deprecated since 1.0.6
     * @see \Plumrocket\ShippingTracking\Helper\Config
     *
     * @return Config
     */
    public function getSysConfig()
    {
        return $this->config;
    }
}
