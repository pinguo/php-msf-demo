<?php
/**
 * @desc: parallel配置 对比之前的批量请求配置
 * @author: leandre <niulingyun@camera360.com>
 * @date: 14/06/2017
 * @copyright All rights reserved.
 */
/**
 * name => [
 *      'service' => 'servA', // 必须在 $config['params']['service'] 里配置 servA 的 host、timeout
 *      'url' => 'urlA',  //   eg: /api/info
 *      'method' => 'GET OR POST'   // 可选 优先使用parallel配置，然后有参数有POST，无参用GET
 *      'timeout' => 200 // 可选， 优先使用
 * ]
 */
$config['params']['parallel'] = [
    'baidu' => [
        'service' => 'baidu',
        'url'     => '/',
        'parser'  => 'none',
    ],
    'baidu1' => [
        'service' => 'baidu',
        'url'     => '/',
        'parser'  => 'none',
    ],
    'baidu2' => [
        'service' => 'baidu',
        'url'     => '/',
        'parser'  => 'none',
    ],
    'baidu3' => [
        'service' => 'baidu',
        'url'     => '/',
        'parser'  => 'none',
    ],
];

return $config;