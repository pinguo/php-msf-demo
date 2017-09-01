<?php
/**
 * TCP底层服务器配置
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

// server服务进程标题前缀
$config['server']['process_title'] = 'msf-demo';
// server服务运行时目录
$config['server']['runtime_path']  = RUNTIME_DIR . '/';
// server服务运行时pid目录
$config['server']['pid_path']      = $config['server']['runtime_path'] . 'pids/';
// 服务器配置
$config['server']['set'] = [
    // swoole server的reactor数量
    'reactor_num'              => 2,
    // swoole server的worker数量
    'worker_num'               => 1,
    // swoole server的task worker数量
    'task_worker_num'          => 2,
    // swoole server的backlog队列长度
    'backlog'                  => 128,
    // swoole server启用open_tcp_nodelay
    'open_tcp_nodelay'         => 1,
    // swoole server数据包分发策略
    'dispatch_mode'            => 1,
    //swoole server设置端口重用
    'enable_reuse_port'        => true,
];
// 异步IO配置
$config['server']['async_io'] = [
    // 设置异步文件IO的操作模式
    'aio_mode'           => SWOOLE_AIO_BASE,
    // 是否异步IO的DNS查询
    'use_async_resolver' => false,
    // 随机返回DNS
    'dns_lookup_random'  => true,
];

//协程超时时间
$config['coroutine']['timeout'] = 5000;

//配置进程
$config['config_manage_enable'] = true;

//自定义定时器
$config['user_timer_enable'] = false;

//是否启用自动reload
$config['auto_reload_enable'] = true;

return $config;
