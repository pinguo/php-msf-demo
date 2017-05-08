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
     * 这里可以进行额外的异步连接池，比如另一组redis/mysql连接
     * @return array
     */
    public function initAsynPools()
    {
        parent::initAsynPools();
    }
}
