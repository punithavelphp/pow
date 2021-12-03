<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Setup;

use Plumrocket\Base\Setup\AbstractUninstall;

class Uninstall extends AbstractUninstall
{
    protected $_configSectionId = 'prshippingtracking';
    protected $_pathes = ['/app/code/Plumrocket/ShippingTracking'];
}
