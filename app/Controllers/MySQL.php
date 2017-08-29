<?php
/**
 * MySQL示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;

class MySQL extends Controller
{
    // MySQL连接池示例
    public function actionBizLists()
    {
        $DBBuilder = $this->getMysqlPool('test')->getDBQueryBuilder();
        $bizLists  = yield $DBBuilder->select("*")->from('t_biz')->go();

        $this->outputJson($bizLists);
    }
}
