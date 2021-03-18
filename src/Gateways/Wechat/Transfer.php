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
 *2019/4/1 8:29 PM
 * 1.0.1
 * 企业付款到零钱
 **/
class Transfer extends WechatBaseObject implements IGatewayRequest
{
    const METHOD = 'mmpaymkttransfers/promotion/transfers';

    /**
     * 申请商户号的appid或商户号绑定的appid
     *
     * @var string
     */
    private $mchAppid = '';

    public function __construct(){
        parent::__construct();

        $this->mchAppid  = self::$config->get('mch_appid', '');;
    }


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
        $totalFee = bcmul($requestParams['amount'], 100, 0);

        $selfParams = [
            'device_info'      => $requestParams['device_info'] ?? '',
            'partner_trade_no' => $requestParams['trans_no'] ?? '',
            'openid'           => $requestParams['openid'] ?? '',
            'check_name'       => $requestParams['check_name'] ?? 'FORCE_CHECK',
            're_user_name'     => $requestParams['re_user_name'] ?? '',
            'amount'           => $totalFee,
            'desc'             => $requestParams['desc'] ?? '',
            'spbill_create_ip' => $requestParams['client_ip'] ?? '',

            'mch_appid'        => $this->mchAppid ?? '',
        ];

        return $selfParams;
    }
}
