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
use App\Tasks\Demo as DemoTask;

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
        $dbs = yield $this->getMysqlPool('master')->go(null, 'show databases');
        $this->outputJson($dbs);
    }

    // 事务示例
    public function actionTransaction()
    {
        $mysqlPool = $this->getMysqlPool('master');
        // 开启一个事务，并返回事务ID
        $id = yield $mysqlPool->goBegin();
        $up = yield $mysqlPool->update('user')->set('name', '徐典阳-1')->where('id', 3)->go($id);
        $ex = yield $mysqlPool->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            yield $mysqlPool->goCommit($id);
            $this->outputJson('commit');
        } else {
            yield $mysqlPool->goRollback($id);
            $this->outputJson('rollback');
        }
    }

    // MySQL代理使用示例
    public function actionProxy()
    {
        $mysqlProxy = $this->getMysqlProxy('master_slave');
        $bizLists   = yield $mysqlProxy->select("*")->from('user')->go();
        $up         = yield $mysqlProxy->update('user')->set('name', '徐典阳-6')->where('id', 3)->go();
        $this->outputJson($bizLists);
    }

    // MySQL代理事务，事务只会在主节点上执行
    public function actionProxyTransaction()
    {
        $mysqlProxy = $this->getMysqlProxy('master_slave');
        // 开启一个事务，并返回事务ID
        $id = yield $mysqlProxy->goBegin();
        $up = yield $mysqlProxy->update('user')->set('name', '徐典阳-2')->where('id', 3)->go($id);
        $ex = yield $mysqlProxy->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            yield $mysqlProxy->goCommit($id);
            $this->outputJson('commit');
        } else {
            yield $mysqlProxy->goRollback($id);
            $this->outputJson('rollback');
        }
    }

    // 通过Task，同步执行MySQL查询（连接池）
    public function actionSyncMySQLPoolTask()
    {
        /**
         * @var DemoTask $demoTask
         */
        $demoTask = $this->getObject(DemoTask::class);
        $user     = yield $demoTask->syncMySQLPool();
        $this->outputJson($user);
    }

    // 通过Task，同步执行MySQL查询（代理）
    public function actionSyncMySQLProxyTask()
    {
        /**
         * @var DemoTask $demoTask
         */
        $demoTask = $this->getObject(DemoTask::class);
        $user     = yield $demoTask->syncMySQLProxy();
        $this->outputJson($user);
    }

    // 通过Task，同步执行MySQL事务查询（连接池）
    public function actionSyncMySQLPoolTaskTransaction()
    {
        /**
         * @var DemoTask $demoTask
         */
        $demoTask = $this->getObject(DemoTask::class);
        $user     = yield $demoTask->syncMySQLPoolTransaction();
        $this->outputJson($user);
    }

    // 通过Task，同步执行MySQL事务查询（代理）
    public function actionSyncMySQLProxyTaskTransaction()
    {
        /**
         * @var DemoTask $demoTask
         */
        $demoTask = $this->getObject(DemoTask::class);
        $user     = yield $demoTask->syncMySQLProxyTransaction();
        $this->outputJson($user);
    }

    public function actionMysqlTest()
    {
        try {
            $m = $this->getObject(\App\Models\Demo::class, [1, 2]);
            yield $m->e();
        } catch (\Exception $e) {
            dump($e);
        }
        $this->output('ok');
    }

    // 分页
    public function actionPage()
    {
        $dbs   = yield $this->getMysqlPool('master')->select("*")->from("user")->limit(3)->go();
//        $total = yield $this->getMysqlPool('master')->select("count(*) as total")->from("user")->go();
//        $res   = ['page' => $dbs['result'], 'count' => $total['result']];
        $res   = ['page' => $dbs['result'], 'count' => 0];
        $this->outputJson($res);
    }

    // 事务示例
    public function actionUserAdd()
    {
        $mysqlPool = $this->getMysqlPool('master');
        $mysqlPool->insert('user')->values(['name' => uniqid()])->go()->break();
        $this->outputJson('ok');
    }

    public function actionMySQLUpdate()
    {
        $mysqlPool = $this->getMysqlPool('master');
        $up = yield $mysqlPool->update('user')->set('name', '徐典阳-1')->where('id', rand(3, 213374))->go();
        $ex = yield $mysqlPool->select('*')->from('user')->where('id', 3)->go();
        $this->outputJson([$up, $ex]);
    }
}
