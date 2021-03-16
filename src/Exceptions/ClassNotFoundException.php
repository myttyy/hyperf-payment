<?php

/*
 * The file is part of the payment lib.
 *
 * (c) Leo <dayugog@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace myttyy\Hyperf\Payment\Exceptions;

/**
 *  myttyy\Hyperf\Payment\Exceptions
 * 
 * 
 *2019/3/30 3:30 PM
 * 1.0.1
 * Hyperf支付
 **/
class ClassNotFoundException extends \RuntimeException
{
    /**
     * GatewayErrorException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
