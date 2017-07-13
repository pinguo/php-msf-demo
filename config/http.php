<?php
/**
 * HTTP服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

// 启用http服务器
$config['http_server']['enable']  = true;
// Http服务器监听的端口
$config['http_server']['port']    = 8000;
// Http服务器监听的地址
$config['http_server']['socket']  = '0.0.0.0';
// Http服务器绑定的域名
$config['http']['domain']         = 'http://localhost:8000';
// http访问时方法的前缀
$config['http']['method_prefix']  = 'http';
// http访问时默认方法
$config['http']['default_method'] = 'Index';
//默认访问的页面
$config['http']['index']          = 'index.html';

return $config;
