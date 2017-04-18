<?php

$config['redis']['active'] = 'develop';
/**
 * 本地环境
 */
$config['redis']['develop']['ip'] = '127.0.0.1';
$config['redis']['develop']['port'] = 6379;
$config['redis']['asyn_max_count'] = 10;

return $config;
