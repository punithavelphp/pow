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
 * @copyright   Copyright (c) 2020 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Model\Service;

class Ups extends \Plumrocket\ShippingTracking\Model\AbstractService
{
    /**
     * Error Key
     */
    const ERROR_KEY = 'Description';

    /**
     * Test track number
     */
    const TEST_TRACK_NUMBER = '1Z12345E0205271688';

    /**
     * Sandbox url
     */
    const SANDBOX_URL = 'https://wwwcie.ups.com/rest/Track';

    /**
     * Production url
     */
    const PRODUCTION_URL = 'https://onlinetools.ups.com/rest/Track';

    /**
     * Sandbox mode enabled
     */
    const SANDBOX_MODE = 1;

    /**
     * Production mode enabled
     */
    const PRODUCTION_MODE = 0;

    /**
     * Index of mode
     */
    const INDEX_OF_MODE = 3;

    /**
     * Excluded error codes
     */
    const EXCLUDE_ERRORS = ['150022', '151018'];

    /**
     * @param string $inquiryNumber
     * @return mixed
     */
    public function getTrackingInfo($inquiryNumber = '', $forTest = false, $testData = [])
    {
        if (! $this->getConfig()->enabledUpsApi($this->getCurrentStoreId()) && ! $forTest) {
            return [];
        }

        if (! $forTest) {
            $authData['username'] = $this->getConfig()->getUserIdUpsApi($this->getCurrentStoreId());
            $authData['password'] = $this->getConfig()->getPasswordUpsApi($this->getCurrentStoreId());
            $authData['access_license'] = $this->getConfig()->getApiKeyUpsApi($this->getCurrentStoreId());
            $url = $this->getUrl();
        } else {
            $authData['username'] = $testData[0];
            $authData['password'] = $testData[1];
            $authData['access_license'] = $testData[2];
            $url  = ((bool)$testData[3]) ? self::SANDBOX_URL : self::PRODUCTION_URL;
        }

        $params = [
            [
                'option' => CURLOPT_CONNECTTIMEOUT,
                'value'  => 5
            ],
            [
                'option' => CURLOPT_TIMEOUT,
                'value'  => 45
            ],
            [
                'option' => CURLOPT_URL,
                'value'  => $url
            ],
            [
                'option' => CURLOPT_HTTPHEADER,
                'value'  => $this->getCurlHeaders()
            ],
            [
                'option' => CURLOPT_RETURNTRANSFER,
                'value'  => 1
            ],
            [
                'option' => CURLOPT_POST,
                'value'  => 1
            ],
            [
                'option' => CURLOPT_POSTFIELDS,
                'value'  => $this->prepareParams($authData, $inquiryNumber)
            ],
        ];

        $response = $this->call($params);

        if ($response === false) {
            $response = $this->fileGetContents($url, [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $this->prepareParams($authData, $inquiryNumber),
                'protocol_version' => 1.1,
                'timeout' => 5,
                'ignore_errors' => true
            ]);
        }

        $response = $this->jsonHelper->jsonDecode($response);

        if (! $forTest) {
            $response = $this->parseTrackInfo($response);
        }

        return $response;
    }

    /**
     * @param $data
     * @return array
     */
    public function testService($data)
    {
        $result = ['result' => false];
        $error = '';

        $info = $this->getTrackingInfo(
            self::TEST_TRACK_NUMBER,
            true,
            $data
        );

        if (isset($info['TrackResponse']['Shipment']['Package']['Activity'])) {
            $result['result'] = true;
        } else {
            $errorCode = isset($info['Fault']['detail']['Errors']['ErrorDetail']['PrimaryErrorCode']['Code'])
                ? $info['Fault']['detail']['Errors']['ErrorDetail']['PrimaryErrorCode']['Code'] : [];

            if (! empty($errorCode) && $errorCode === self::EXCLUDE_ERRORS[$data[self::INDEX_OF_MODE]]) {
                $result['result'] = true;

                return $result;
            }

            array_walk_recursive($info, function($value, $key) use (&$error) {
                if ($key == self::ERROR_KEY) {
                    $error = $value;
                }
            }, $error);

            $result['error'] = $error;
        }

        return $result;
    }

    /**
     * @param $info
     * @return array
     */
    public function parseTrackInfo($info)
    {
        $result = [];

        if (isset($info['TrackResponse']['Shipment']['Package']['Activity'])) {
            $info = $info['TrackResponse']['Shipment']['Package']['Activity'];

            if (isset($info['Status'], $info['Date'], $info['Time'])) {
                $result['info'][0] = $this->dataSorting($info);
            } else {
                foreach ($info as $key => $activity) {
                    $result['info'][$key] = $this->dataSorting($activity);
                }
            }

            $result['columns'] = [
                __($this->locationColumnName),
                __($this->dateColumnName),
                __($this->timeColumnName),
                __($this->statusColumnName)
            ];
        }

        return $result;
    }

    /**
     * @param $activity
     * @return array
     */
    private function dataSorting($activity)
    {
        if (isset($activity['ActivityLocation']['Address'])) {
            $address = $activity['ActivityLocation']['Address'];
            $location = ($address['City'] ?? '')
                . ', ' . ($address['StateProvinceCode'] ?? '')
                . ', ' . (isset($address['CountryCode']) ? $this->getCountryName($address['CountryCode']) : '');
            $result['location'] = $location;
        } else {
            $result['location'] = '';
        }

        $result['date'] = isset($activity['Date'])
            ? date($this->getDateFormat(), strtotime($activity['Date'])) : '';

        $result['time'] = isset($activity['Time'])
            ? $this->timezone->date($activity['Time'])->format($this->getTimeFormat()) : '';

        $result['status'] = $activity['Status']['Description'] ?? '';

        return $result;
    }

    /**
     * @param $inquiryNumber
     * @return string
     */
    private function prepareParams($authData, $inquiryNumber)
    {
        $upsParam = [
            'UPSSecurity' => [
                'UsernameToken' => [
                    'Username' => $authData['username'],
                    'Password' => $authData['password']
                ],
                'ServiceAccessToken' => [
                    'AccessLicenseNumber' => $authData['access_license']
                ]
            ],
            'TrackRequest' => [
                'Request' => [
                    'RequestOption' => '1',
                    'TransactionReference' => [
                        'CustomerContext' => 'Request by data'
                    ]
                ],
                'InquiryNumber' => $inquiryNumber
            ]
        ];

        return $this->jsonHelper->jsonEncode($upsParam);
    }

    /**
     * @return array
     */
    private function getCurlHeaders()
    {
        return [
            'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept',
            'Access-Control-Allow-Methods: POST',
            'Access-Control-Allow-Origin: *',
            'Content-Type: application/json'
        ];
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        if ($this->getConfig()->getSandboxUpsApi($this->getCurrentStoreId())) {
            return self::SANDBOX_URL;
        }

        return self::PRODUCTION_URL;
    }
}
