<?php
/**
 * AppServer
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App;

use PG\MSF\MSFServer;

class AppServer extends MSFServer
{
    const SERVER_NAME = SYSTEM_NAME;

    /**
     *
     * {@inheritDoc}
     * @see \PG\MSF\MSFServer::onInitTimer()
     */
    public function onInitTimer()
    {
        $this->registerTimer(1000, function(\PG\MSF\Base\Core $runInstance, $timerId, $params) {
            echo "user timer echo 1s per\n";
        });

        $this->registerTimer(2000, function(\PG\MSF\Base\Core $runInstance, $timerId, $params) {
            echo "user timer echo 2s per\n";
        });
    }
}
