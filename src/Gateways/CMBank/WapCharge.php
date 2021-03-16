<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Gateways\CMBank;

use myttyy\Hyperf\Payment\Contracts\IGatewayRequest;
use myttyy\Hyperf\Payment\Exceptions\GatewayException;

/**
 * @package Payment\Gateways\Alipay
 * @author  : Leo
 * @email   : dayugog@gmail.com
 * @date    : 2019/3/28 10:21 PM
 * @version : 1.0.0
 * @desc    : wap支付
 **/
class WapCharge extends CMBaseObject implements IGatewayRequest
{
    const METHOD = 'netpayment/BaseHttp.dll?MB_EUserPay';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        $this->gatewayUrl = 'https://netpay.cmbchina.com/%s';
        if ($this->isSandbox) {
            $this->gatewayUrl = 'http://121.15.180.66:801/%s';
        }

        try {
            return $this->requestCMBApi(self::METHOD, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getRequestParams(array $requestParams)
    {
        $nowTime    = time();
        $timeExpire = $requestParams['time_expire'] ?? 0;
        $timeExpire = $timeExpire - $nowTime;
        if ($timeExpire < 3) {
            $timeExpire = 30; // 如果设置不合法，默认改为30
        }

        $params = [
            'dateTime'         => date('YmdHis', $nowTime),
            'branchNo'         => self::$config->get('branch_no', ''),
            'merchantNo'       => self::$config->get('mch_id', ''),
            'date'             => date('Ymd', $requestParams['date'] ?? $nowTime),
            'orderNo'          => $requestParams['trade_no'] ?? '',
            'amount'           => $requestParams['amount'] ?? '', // 固定两位小数，最大11位整数
            'expireTimeSpan'   => $timeExpire,
            'payNoticeUrl'     => self::$config->get('notify_url', ''),
            'payNoticePara'    => $requestParams['return_param'] ?? '',
            'returnUrl'        => self::$config->get('return_url', ''),
            'clientIP'         => $requestParams['client_ip'] ?? '',
            'cardType'         => self::$config->get('limit_pay', ''), // A:储蓄卡支付，即禁止信用卡支付
            'agrNo'            => $requestParams['agr_no'] ?? '',
            'merchantSerialNo' => $requestParams['merchant_serial_no'] ?? '',
            'userID'           => $requestParams['user_id'] ?? '',
            'mobile'           => $requestParams['mobile'] ?? '',
            'lon'              => $requestParams['lon'] ?? '',
            'lat'              => $requestParams['lat'] ?? '',
            'riskLevel'        => $requestParams['risk_level'] ?? '',
            'signNoticeUrl'    => self::$config->get('sign_notify_url', ''),
            'signNoticePara'   => $requestParams['return_param'] ?? '',
            //'extendInfo' => '',
            //'extendInfoEncrypType' => '',
        ];

        return $params;
    }
}
