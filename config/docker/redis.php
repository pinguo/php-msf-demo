<?php
/**
 * 本地环境
 */
$config['redis']['tw']['ip']               = '127.0.0.1';
$config['redis']['tw']['port']             = 6379;
//$config['redis']['tw']['password']       = 'xxxx';
//$config['redis']['tw']['select']         = 1;
// Redis序列化选项等同于phpredis序列化的各个选项如:\Redis::SERIALIZER_PHP,\Redis::SERIALIZER_IGBINARY
//$config['redis']['tw']['redisSerialize'] = \Redis::SERIALIZER_PHP;
// PHP序列化选项,为了兼容yii迁移项目的set,get,mset,mget,选项如:\Redis::SERIALIZER_PHP,\Redis::SERIALIZER_IGBINARY
//$config['redis']['tw']['phpSerialize']   = \Redis::SERIALIZER_NONE;
// 是否将key md5后储存,默认为0,开启为1
//$config['redis']['tw']['hashKey']        = 1;
// 设置key的前缀
//$config['redis']['tw']['keyPrefix']      = 'hotpot_';

$config['redis']['tw1']['ip']               = '127.0.0.1';
$config['redis']['tw1']['port']             = 6380;

$config['redis']['read_only']['ip']         = '127.0.0.1';
$config['redis']['read_only']['port']       = 6381;

$config['redis_proxy']['cluster'] = [
    'pools' => [
        'tw'  => 1,
        'tw1' => 1,
    ],
    'mode' => \PG\MSF\Marco::CLUSTER,
];

//$config['redis_proxy']['master_slave'] = [
//    'pools' => [
//        'tw', 'read_only',
//    ],
//    'mode' => \PG\MSF\Marco::MASTER_SLAVE,
//];

return $config;
