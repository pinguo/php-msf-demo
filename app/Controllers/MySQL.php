<?php
/**
 * MySQL示例控制器
 *
 * app/data/demo.sql可以导入到mysql再运行示例方法
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
        // SQL DBBuilder更多参考 https://github.com/jstayton/Miner
        $bizLists  = yield $this->getMysqlPool('master')->select("*")->from('biz')->go();
        $this->outputJson($bizLists);
    }

    // 直接执行sql
    public function actionShowDB()
    {
        /**
         * @var \PG\MSF\Pools\Miner $DBBuilder
         */
        $dbs = yield $this->getMysqlPool('master')->go(null, 'show databases');
        $this->outputJson($dbs);
    }

    // 事务示例
    public function actionTransaction()
    {
        /**
         * @var \PG\MSF\Pools\Miner|\PG\MSF\Pools\MysqlAsynPool $mysqlPool
         */
        $mysqlPool = $this->getMysqlPool('master');
        // 开启一个事务，并返回事务ID
        $id = yield $mysqlPool->coroutineBegin();
        $up = yield $mysqlPool->update('user')->set('name', '徐典阳-1')->where('id', 3)->go($id);
        $ex = yield $mysqlPool->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            yield $mysqlPool->coroutineCommit($id);
            $this->outputJson('commit');
        } else {
            yield $mysqlPool->coroutineRollback($id);
            $this->outputJson('rollback');
        }
    }

    // MySQL代理使用示例
    public function actionProxy()
    {
        /**
         * @var \PG\MSF\Pools\Miner|\PG\MSF\Pools\MysqlAsynPool $mysqlProxy
         */
        $mysqlProxy = $this->getMysqlProxy('master_slave');
        $bizLists   = yield $mysqlProxy->select("*")->from('user')->go();
        $up         = yield $mysqlProxy->update('user')->set('name', '徐典阳-6')->where('id', 3)->go();
        $this->outputJson($bizLists);
    }

    // MySQL代理事务，事务只会在主节点上执行
    public function actionProxyTransaction()
    {
        /**
         * @var \PG\MSF\Pools\Miner|\PG\MSF\Pools\MysqlAsynPool $mysqlProxy
         */
        $mysqlProxy = $this->getMysqlProxy('master_slave');
        // 开启一个事务，并返回事务ID
        $id = yield $mysqlProxy->coroutineBegin();
        $up = yield $mysqlProxy->update('user')->set('name', '徐典阳-2')->where('id', 3)->go($id);
        $ex = yield $mysqlProxy->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            yield $mysqlProxy->coroutineCommit($id);
            $this->outputJson('commit');
        } else {
            yield $mysqlProxy->coroutineRollback($id);
            $this->outputJson('rollback');
        }
    }
}
