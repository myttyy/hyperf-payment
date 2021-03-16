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
 *2019/4/7 9:58 AM
 * 1.0.1
 * Hyperf支付
 **/
class PublicKeyQuery extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'risk/getpublickey';

    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams)
    {
        $this->setGatewayUrl('https://fraud.mch.weixin.qq.com/%s');

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
        return [];
    }
}
