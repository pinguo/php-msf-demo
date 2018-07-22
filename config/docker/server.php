<?php
/**
 * 服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
$config['server']['async_io']['thread_num'] = 4; // 同 worker_num 值
$config['server']['set']['reactor_num'] = 2; //reactor thread num
$config['server']['set']['worker_num'] = 4;  //worker process num
$config['server']['set']['backlog'] = 4096; //listen backlog
$config['server']['set']['task_worker_num'] = 1; //task process num

return $config;
