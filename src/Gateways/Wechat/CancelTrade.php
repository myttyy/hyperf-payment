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
 *2019/11/26 6:55 PM
 * 1.0.1
 * 撤销订单，支付交易返回失败或支付系统超时，调用该接口撤销交易
 **/
class CancelTrade extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'secapi/pay/reverse';

    /**
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    protected function getSelfParams(array $requestParams)
    {
        try {
            return $this->requestWXApi(self::METHOD, $requestParams);
        } catch (GatewayException $e) {
            throw $e;
        }
    }

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     */
    public function request(array $requestParams)
    {
        $selfParams = [
            'transaction_id' => $requestParams['transaction_id'] ?? '',
            'out_trade_no'   => $requestParams['trade_no'] ?? '',
        ];

        return $selfParams;
    }
}
