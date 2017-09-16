<?php
/**
 * HTTP服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

// Http服务器监听的端口
$config['http_server']['port']    = 8000;
// Http服务器监听的地址
$config['http_server']['socket']  = '0.0.0.0';
// Http服务器绑定的域名
$config['http']['domain']         = [
    '127.0.0.1' => [
        'root'  => ROOT_PATH . '/www/',
        'index' => ROOT_PATH . '/www/index.html',
    ],
    'localhost' => [
        'root'  => ROOT_PATH . '/www/',
        'index' => ROOT_PATH . '/www/index.html',
    ],
];
// http访问时默认方法
$config['http']['default_method'] = 'Index';
// dns缓存有效时间，单位秒
$config['http']['dns']['expire']  = 10;
return $config;
