<?php
/**
 * 北京生产环境TCP底层服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
$config['server']['async_io']['thread_num'] = 4;
$config['server']['set']['reactor_num']     = 4;
$config['server']['set']['worker_num']      = 4;
$config['server']['set']['backlog']         = 4096;
$config['server']['set']['task_worker_num'] = 8;

return $config;
