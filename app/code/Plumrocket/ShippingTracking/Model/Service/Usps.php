<?php
/**
 * @package     Plumrocket_ShippingTracking
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (https://www.plumrocket.com)
 * @license     https://www.plumrocket.com/license/  End-user License Agreement
 */

namespace Plumrocket\ShippingTracking\Model\Service;

class Usps extends \Plumrocket\ShippingTracking\Model\AbstractService
{
    const TEST_TRACK_NUMBER = '9361289725009477230295';

    /** @var string  */
    const USPS_URL = 'http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2&XML=';

    /**
     * @param string $inquiryNumber
     * @param bool   $forTest
     * @param array  $testData
     * @return mixed
     */
    public function getTrackingInfo($inquiryNumber = '', $forTest = false, $testData = [])
    {
        if (!$this->getConfig()->enabledUspsApi($this->getCurrentStoreId()) && !$forTest) {
            return [];
        }

        if (!$forTest) {
            $authData['user_id'] = $this->getConfig()->getUserIdUspsApi($this->getCurrentStoreId());
        } else {
            $authData['user_id'] = $testData[0];
        }

        $response = file_get_contents(
            self::USPS_URL . $this->prepareParams($authData, $inquiryNumber)
        );

        if (! $response) {
            return null;
        }

        $result = simplexml_load_string($response);
        if (!$forTest) {
            $result = $this->parseTrackInfo($result);
        }

        return $result;
    }

    /**
     * @param $data
     * @return array
     */
    public function testService($data)
    {
        $result = ['result' => true, 'error' => ''];

        $info = $this->getTrackingInfo(
            self::TEST_TRACK_NUMBER,
            true,
            $data
        );

        if (isset($info->Description)) {
            $desc = (array)$info->Description;
            $result = [
                'result' => false,
                'error' => $desc[0]
            ];
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $response
     * @return array
     */
    public function parseTrackInfo($response): array
    {
        $result = [];
        $key = 0;

        if (isset($response->TrackInfo->TrackDetail)) {
            foreach ($response->TrackInfo->TrackDetail as $info) {
                $pattern = '#(.*?), (\d{1,2}\/\d{1,2}\/\d{4}|\w* \d{1,2}, \d{4}), (\d{1,2}:\d{1,2}\s(?:am|pm)), (.*)#';
                preg_match($pattern, $info, $data);

                $result['info'][$key]['location'] = $data[4];
                $result['info'][$key]['date'] = $data[2];
                $result['info'][$key]['time'] = $data[3];
                $result['info'][$key]['status'] = $data[1];
                $key++;
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
     * @param $authData
     * @param $inquiryNumber
     * @return string
     */
    private function prepareParams($authData, $inquiryNumber)
    {
        $params = '<TrackRequest USERID="' . urlencode($authData['user_id'])
            . '"> <TrackID ID="' . $inquiryNumber . '"></TrackID></TrackRequest>';

        return urlencode($params);
    }
}
