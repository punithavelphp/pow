<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Block\Adminhtml\System\Config\Connection;

use Plumrocket\ShippingTracking\Helper\Config;

class Ups extends \Plumrocket\ShippingTracking\Block\Adminhtml\System\Config\Connection\AbstractButton
{
    /**
     * Service prefix
     */
    const SERVICE_PREFIX = 'prshippingtracking_services_ups_api_';

    /**
     * @param null $htmlId
     * @return string
     */
    public function getOnclick($htmlId = null)
    {
        return sprintf(
            'window.prTrackingTestConnection(\'%s\', \'%s\', \'%s\'); return false;',
            $this->getUrl(Config::TEST_CONNECTION_URL, ['carrier' => Config::UPS]),
            $htmlId,
            $this->getFieldIds()
        );
    }

    /**
     * @return string
     */
    public function getFieldIds()
    {
        $suffixIds = [
            'user_id',
            'password',
            'api_key',
            'sandbox_mode'
        ];
        $ids = implode("," . self::SERVICE_PREFIX, $suffixIds);

        return self::SERVICE_PREFIX . $ids;
    }
}
