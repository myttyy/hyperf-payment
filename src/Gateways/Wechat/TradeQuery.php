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
use myttyy\Hyperf\Payment\Payment;

/**
 *  myttyy\Hyperf\Payment\Gateways\Wechat
 * 
 * 
 *2019/4/1 8:27 PM
 * 1.0.1
 * 该接口提供所有微信支付订单的查询
 **/
class TradeQuery extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'pay/orderquery';

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
            'transaction_id' => $requestParams['transaction_id'] ?? '',
            'out_trade_no'   => $requestParams['trade_no'] ?? '',
        ];

        return $selfParams;
    }
}
