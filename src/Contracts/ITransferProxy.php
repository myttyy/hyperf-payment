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

/**
 * 
 * 
 * 
 *2019/3/28 10:32 PM
 * 1.0.1
 * 转账交易接口
 **/
interface ITransferProxy
{
    /**
     * 转账
     * @param array $requestParams
     * @return mixed
     */
    public function transfer(array $requestParams);
}
