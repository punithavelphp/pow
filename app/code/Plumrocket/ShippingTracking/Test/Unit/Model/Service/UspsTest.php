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
 * @package     Plumrocket_CookieConsent
 * @copyright   Copyright (c) 2020 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

declare(strict_types=1);

namespace Plumrocket\ShippingTracking\Test\Unit\Model\Service;

use PHPUnit\Framework\TestCase;
use Plumrocket\ShippingTracking\Model\Service\Usps;

/**
 * @since 1.0.6
 */
class UspsTest extends TestCase
{
    /**
     * @var \Plumrocket\ShippingTracking\Model\Service\Usps
     */
    private $usps;

    protected function setUp(): void
    {
        $this->usps = $this
            ->getMockBuilder(Usps::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['parseTrackInfo'])
            ->getMock();
    }

    /**
     * Tracking Number - 9361289725009477230295
     */
    public function testParseSampleResponse(): void
    {
        $trackInfo = new \stdClass();
        $trackInfo->TrackDetail = [
            "Out for Delivery, 02/23/2021, 11:36 am, SIERRA VISTA, AZ 85650",
            "Arrived at Post Office, 02/23/2021, 11:25 am, SIERRA VISTA, AZ 85635",
            "USPS in possession of item, February 23, 2021, 12:41 am, SIERRA VISTA, AZ 85635",
            "Departed Shipping Partner Facility, USPS Awaiting Item, February 22, 2021, 8:40 pm, PHOENIX, AZ 85043",
            "Departed Shipping Partner Facility, USPS Awaiting Item, February 19, 2021, 3:40 pm, HEBRON, KY 41048",
            "Departed Shipping Partner Facility, USPS Awaiting Item, February 18, 2021, 4:01 pm, INDIANAPOLIS, IN 46231",
            "Picked Up by Shipping Partner, USPS Awaiting Item, February 18, 2021, 6:16 am, INDIANAPOLIS, IN 46231"
        ];

        $response = new \stdClass();
        $response->TrackInfo = $trackInfo;

        $expectedInfo = [
            [
                'location' => 'SIERRA VISTA, AZ 85650',
                'date' => '02/23/2021',
                'time' => '11:36 am',
                'status' => 'Out for Delivery',
            ],
            [
                'location' => 'SIERRA VISTA, AZ 85635',
                'date' => '02/23/2021',
                'time' => '11:25 am',
                'status' => 'Arrived at Post Office',
            ],
            [
                'location' => 'SIERRA VISTA, AZ 85635',
                'date' => 'February 23, 2021',
                'time' => '12:41 am',
                'status' => 'USPS in possession of item',
            ],
            [
                'location' => 'PHOENIX, AZ 85043',
                'date' => 'February 22, 2021',
                'time' => '8:40 pm',
                'status' => 'Departed Shipping Partner Facility, USPS Awaiting Item',
            ],
            [
                'location' => 'HEBRON, KY 41048',
                'date' => 'February 19, 2021',
                'time' => '3:40 pm',
                'status' => 'Departed Shipping Partner Facility, USPS Awaiting Item',
            ],
            [
                'location' => 'INDIANAPOLIS, IN 46231',
                'date' => 'February 18, 2021',
                'time' => '4:01 pm',
                'status' => 'Departed Shipping Partner Facility, USPS Awaiting Item',
            ],
            [
                'location' => 'INDIANAPOLIS, IN 46231',
                'date' => 'February 18, 2021',
                'time' => '6:16 am',
                'status' => 'Picked Up by Shipping Partner, USPS Awaiting Item',
            ]
        ];

        $parsedData = $this->usps->parseTrackInfo($response);
        self::assertSame($expectedInfo, $parsedData['info']);
    }
}
