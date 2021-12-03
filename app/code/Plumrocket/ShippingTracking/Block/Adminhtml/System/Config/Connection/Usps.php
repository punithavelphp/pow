<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Block\Adminhtml\System\Config\Connection;

use Plumrocket\ShippingTracking\Helper\Config;

class Usps extends AbstractButton
{
    /**
     * @var string
     */
    const SERVICE_PREFIX = 'prshippingtracking_services_usps_api_';

    /**
     * @param null $htmlId
     * @return string
     */
    public function getOnclick($htmlId = null)
    {
        return sprintf(
            'window.prTrackingTestConnection(\'%s\', \'%s\', \'%s\'); return false;',
            $this->getUrl(Config::TEST_CONNECTION_URL, ['carrier' => Config::USPS]),
            $htmlId,
            $this->getFieldIds()
        );
    }

    /**
     * @return string
     */
    public function getFieldIds()
    {
        return self::SERVICE_PREFIX . 'user_id';
    }
}
