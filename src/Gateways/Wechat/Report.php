<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Gateways\Wechat;

use myttyy\Hyperf\Payment\Contracts\IGatewayRequest;
use myttyy\Hyperf\Payment\Exceptions\GatewayException;

/**
 *  myttyy\Hyperf\Payment\Gateways\Wechat
 * 
 * 
 *2019/4/7 9:36 AM
 * 1.0.1
 * 交易保障，上报时间
 **/
class Report extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'payitil/report';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        try {
            return $this->requestWXApi(self::METHOD, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getSelfParams(array $requestParams)
    {
        $selfParams = [
            'device_info'   => $requestParams['device_info'] ?? '',
            'interface_url' => $requestParams['interface_url'] ?? '',
            'user_ip'       => $requestParams['user_ip'] ?? '',
            'trades'        => $requestParams['trades'] ?? '',
        ];

        return $selfParams;
    }
}
