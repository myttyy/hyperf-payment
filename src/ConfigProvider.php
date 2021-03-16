<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace myttyy\Hyperf\Payment;

use myttyy\Hyperf\Payment\Listener\PaymentListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        !defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
        
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            // 与 commands 类似
            'listeners' => [
                PaymentListener::class,
            ],
            // 组件默认配置文件，即执行命令后会把 source 的对应的文件复制为 destination 对应的的文件
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'description of this config file.', // 描述
                    // 建议默认配置放在 publish 文件夹中，文件命名和组件名称相同
                    'source' => __DIR__ . '/../publish/payment.php',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/payment.php', // 复制为这个路径下的该文件
                ],
            ],
        ];
    }
}
