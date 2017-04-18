<?php
/**
 * Created by PhpStorm.
 * User: niulingyun
 * Date: 17-1-9
 * Time: 下午6:05
 */

$config['http_server']['port'] = 1234;
$config['http_server']['socket'] = '0.0.0.0';
$config['http']['domain'] = 'http://localhost:1234';
/**
 * http访问时方法的前缀
 */
$config['http']['method_prefix'] = 'http';
/**
 * http访问时默认方法
 */
$config['http']['default_method'] = 'Index';
//默认访问的页面
$config['http']['index'] = 'index.html';

return $config;
