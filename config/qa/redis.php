<?php
/**
 * 本地环境
 */
$config['redis']['p1']['ip']               = '127.0.0.1';
$config['redis']['p1']['port']             = 6380;
$config['redis']['p2']['ip']               = '127.0.0.1';
$config['redis']['p2']['port']             = 6380;
$config['redis']['p3']['ip']               = '127.0.0.1';
$config['redis']['p3']['port']             = 6380;

$config['redis']['p4']['ip']               = '127.0.0.1';
$config['redis']['p4']['port']             = 6380;
$config['redis']['p5']['ip']               = '127.0.0.1';
$config['redis']['p5']['port']             = 6380;
$config['redis']['p6']['ip']               = '127.0.0.1';
$config['redis']['p6']['port']             = 6380;

$config['redis_proxy']['master_slave'] = [
    'pools' => ['p1', 'p2', 'p3'],
    'mode' => \PG\MSF\Macro::MASTER_SLAVE,
];

$config['redis_proxy']['cluster'] = [
    'pools' => [
        'p4' => 1,
        'p5' => 1,
        'p6' => 1
    ],
    'mode' => \PG\MSF\Macro::CLUSTER,
];

return $config;
