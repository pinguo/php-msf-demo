<?php
/**
 * 本地环境
 */
$config['redis']['p1']['ip']               = '127.0.0.1';
$config['redis']['p1']['port']             = 6379;
//$config['redis']['p1']['password']       = 'xxxx';
//$config['redis']['p1']['select']         = 1;
// Redis序列化选项等同于phpredis序列化的各个选项如:\Redis::SERIALIZER_PHP,\Redis::SERIALIZER_IGBINARY
//$config['redis']['p1']['redisSerialize'] = \Redis::SERIALIZER_PHP;
// PHP序列化选项,为了兼容yii迁移项目的set,get,mset,mget,选项如:\Redis::SERIALIZER_PHP,\Redis::SERIALIZER_IGBINARY
//$config['redis']['p1']['phpSerialize']   = \Redis::SERIALIZER_NONE;
// 是否将key md5后储存,默认为0,开启为1
//$config['redis']['p1']['hashKey']        = 1;
// 设置key的前缀
//$config['redis']['p1']['keyPrefix']      = 'demo_';

$config['redis']['p2']['ip']               = '127.0.0.1';
$config['redis']['p2']['port']             = 6380;

$config['redis']['p3']['ip']               = '127.0.0.1';
$config['redis']['p3']['port']             = 6381;

$config['redis']['p4']['ip']               = '127.0.0.1';
$config['redis']['p4']['port']             = 7379;

$config['redis']['p5']['ip']               = '127.0.0.1';
$config['redis']['p5']['port']             = 7380;

$config['redis']['p6']['ip']               = '127.0.0.1';
$config['redis']['p6']['port']             = 7381;

$config['redis_proxy']['master_slave'] = [
    'pools' => ['p1', 'p2', 'p3'],
    'mode' => \PG\MSF\Marco::MASTER_SLAVE,
];

$config['redis_proxy']['cluster'] = [
    'pools' => [
        'p4' => 1,
        'p5' => 1,
        'p6' => 1
    ],
    'mode' => \PG\MSF\Marco::CLUSTER,
];

return $config;
