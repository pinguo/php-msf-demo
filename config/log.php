<?php
/**
 * 日志配置
 * Created by PhpStorm.
 * User: niulingyun
 * Date: 17-1-11
 * Time: 下午2:40
 */

$config['server']['log'] = [
    'handlers' => [
        'application' => [
            'levelList' => [
                \PG\Log\PGLog::EMERGENCY,
                \PG\Log\PGLog::ALERT,
                \PG\Log\PGLog::CRITICAL,
                \PG\Log\PGLog::ERROR,
                \PG\Log\PGLog::WARNING
            ],
            'dateFormat' => "Y/m/d H:i:s",
            'format' => "%datetime% [%level_name%] [%channel%] [logid:%logId%] %message%\n",
            'stream' => RUNTIME_DIR . '/application.log',
            'buffer' => 0,
        ],
        'notice' => [
            'levelList' => [
                \PG\Log\PGLog::NOTICE,
                \PG\Log\PGLog::INFO,
                \PG\Log\PGLog::DEBUG
            ],
            'dateFormat' => "Y/m/d H:i:s",
            'format' => "%datetime% [%level_name%] [%channel%] [logid:%logId%] %message%\n",
            'stream' => RUNTIME_DIR . '/notice.log',
            'buffer' => 0,
        ]
    ]
];

return $config;