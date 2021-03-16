<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace myttyy\Hyperf\Payment\Listener;

use myttyy\Hyperf\Payment\Adapter\AlipayFactory;
use myttyy\Hyperf\Payment\Adapter\WxpayFactory;
use myttyy\Hyperf\Payment\Adapter\CMBankProxy;
use myttyy\Hyperf\Payment\Client;
use myttyy\Hyperf\Payment\Event\PaymentMeta;
use Psr\Container\ContainerInterface;

// Hyperf 框架特有
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Codec\Json;
use Hyperf\Logger\LoggerFactory;
// use Hyperf\Contract\StdoutLoggerInterface;
// use Hyperf\Contract\ConfigInterface;

class PaymentListener implements ListenerInterface
{
    /**
     * @var string
     */
    private $proxy;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * 日志记录器
     */
    private $logger;

    public function __construct(ContainerInterface $container, ConfigInterface $config)
    {
        $this->container = $container;
        $this->config = $config;
        $this->logger = $this->container->get(LoggerFactory::class)->get('payment');
    }

    public function listen(): array
    {
        return [
            PaymentMeta::class,
        ];
    }

    /**
     * 事件监听  --- 日志记录
     *  mytty 2021年3月16日 12:00:42
     * @param object $event
     * @return void
     */
    public function process(object $event)
    {
        /**
         * @var PaymentMeta
         */
        $factory = $event->factory;
        if ($factory instanceof AlipayFactory) {
            $this->proxy = Client::ALIPAY;
        } elseif ($factory instanceof WxpayFactory) {
            $this->proxy = Client::WECHAT;
        } elseif ($factory instanceof CMBankProxy) {
            $this->proxy = Client::CMB;
        }
        $this->logger->info(sprintf('调取支付渠道[%s],请求参数[%s],数据响应[%s]', $this->proxy, Json::encode($event->request), Json::encode($event->response)));
    }
}
