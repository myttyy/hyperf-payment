<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Contracts;

use myttyy\Hyperf\Payment\Exceptions\GatewayException;

/**
 * 
 * 
 * 
 *2019/3/30 10:29 AM
 * 1.0.1
 * 网关功能标准接口
 **/
interface IGatewayRequest
{
    /**
     * 获取第三方返回结果
     * @param array $requestParams
     * @return mixed
     * @throws GatewayException
     */
    public function request(array $requestParams);
}
