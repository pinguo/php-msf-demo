<?php
/**
 * MongoDb配置文件
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

$config['mongodb']['test']['server'] = 'mongodb://127.0.0.1:27017';
$config['mongodb']['test']['options'] = ['connect' => true];
$config['mongodb']['test']['driverOptions'] = [];

return $config;