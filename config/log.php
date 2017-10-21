<?php
/**
 * 日志配置文件
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
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
