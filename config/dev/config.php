<?php
/**
 * 服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
$config['server']['async_io']['thread_num'] = 1; // 同 worker_num 值
$config['server']['set']['reactor_num'] = 1; //reactor thread num
$config['server']['set']['worker_num'] = 1;  //worker process num
$config['server']['set']['backlog'] = 4096; //listen backlog
$config['server']['set']['task_worker_num'] = 1; //task process num

$config['http_server']['port'] = 8000;
$config['http_server']['socket'] = '0.0.0.0';
$config['http']['domain'] = 'http://localhost:8000';
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
