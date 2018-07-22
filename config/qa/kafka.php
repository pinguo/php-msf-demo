<?php
/**
 * RabbitMQ 配置
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 30/10/2017
 */

$config['kafka'] = [
    'local' => [
        'socket.keepalive.enable' => true,
        'bootstrap.servers' => '127.0.0.1:9092',
        'group.id' => 'default'
    ]
];

return $config;
