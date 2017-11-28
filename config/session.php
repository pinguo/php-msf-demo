<?php
/**
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 27/11/2017
 */

$config['session'] = [
    //'handler' => \PG\MSF\Marco::SESSION_FILE,
    //'savePath' => '/tmp/msf',

    //'handler' => \PG\MSF\Marco::SESSION_REDIS,
    //'savePath' => 'p1',

    'handler' => \PG\MSF\Macro::SESSION_REDIS_PROXY,
    'savePath' => 'master_slave',
    
    'sessionName' => 'msf_session',
    'maxLifeTime' => 1440
];

return $config;
