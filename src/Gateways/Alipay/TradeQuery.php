<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Gateways\Alipay;

use myttyy\Hyperf\Payment\Contracts\IGatewayRequest;
use myttyy\Hyperf\Payment\Exceptions\GatewayException;
use myttyy\Hyperf\Payment\Helpers\ArrayUtil;
use myttyy\Hyperf\Payment\Payment;

/**
 *  myttyy\Hyperf\Payment\Gateways\Alipay
 * 
 * 
 *2019/3/31 9:15 AM
 * 1.0.1
 * Hyperf支付
 **/
class TradeQuery extends AliBaseObject implements IGatewayRequest
{
    const METHOD = 'alipay.trade.query';

    /**
     * @param array $requestParams
     * @return mixed
     */
    protected function getBizContent(array $requestParams)
    {
        $bizContent = [
            'out_trade_no'  => $requestParams['trade_no'] ?? '',
            'trade_no'      => $requestParams['transaction_id'] ?? '',
            'query_options' => $requestParams['query_options'] ?? '',
            'org_pid'       => $requestParams['org_pid'] ?? '',
        ];
        $bizContent = ArrayUtil::paraFilter($bizContent);

        return $bizContent;
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
            $params = $this->buildParams(self::METHOD, $requestParams);
            $ret    = $this->get($this->gatewayUrl, $params);
            $retArr = json_decode($ret, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new GatewayException(sprintf('format refund data get error, [%s]', json_last_error_msg()), Payment::FORMAT_DATA_ERR, ['raw' => $ret]);
            }

            $content = $retArr['alipay_trade_query_response'];
            if ($content['code'] !== self::REQ_SUC) {
                throw new GatewayException(sprintf('request get failed, msg[%s], sub_msg[%s]', $content['msg'], $content['sub_msg']), Payment::SIGN_ERR, $content);
            }

            $signFlag = $this->verifySign($content, $retArr['sign']);
            if (!$signFlag) {
                throw new GatewayException('check sign failed', Payment::SIGN_ERR, $retArr);
            }

            return $content;
        } catch (GatewayException $e) {
            throw $e;
        }
    }
}
