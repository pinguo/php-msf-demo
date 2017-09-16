<?php
/**
 * Demo Task
 *
 * 注意理论上本文件代码应该在Tasker进程中执行
 */

namespace App\Tasks;

use \PG\MSF\Tasks\Task;

/**
 * Class Demo
 * @package App\Tasks
 */
class Demo extends Task
{
    /**
     * 连接池执行同步查询
     *
     * @return array
     */
    public function syncMySQLPool()
    {
        $user = $this->getMysqlPool('master')->select("*")->from("user")->go();
        return $user;
    }

    /**
     * 代理执行同步查询
     *
     * @return array
     */
    public function syncMySQLProxy()
    {
        $user = $this->getMysqlProxy('master_slave')->select("*")->from("user")->go();
        return $user;
    }

    /**
     * 连接池执行同步事务
     *
     * @return boolean
     */
    public function syncMySQLPoolTransaction()
    {
        $mysqlPool = $this->getMysqlPool('master');
        $id = $mysqlPool->begin();
        // 开启一个事务，并返回事务ID
        $up = $mysqlPool->update('user')->set('name', '徐典阳-1')->where('id', 3)->go($id);
        $ex = $mysqlPool->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            $mysqlPool->commit();
            return true;
        } else {
            $mysqlPool->rollback();
            return false;
        }
    }

    /**
     * 代理执行同步事务
     *
     * @return boolean
     */
    public function syncMySQLProxyTransaction()
    {
        $mysqlPool = $this->getMysqlProxy('master_slave');
        $id = $mysqlPool->begin();
        // 开启一个事务，并返回事务ID
        $up = $mysqlPool->update('user')->set('name', '徐典阳-1')->where('id', 3)->go($id);
        $ex = $mysqlPool->select('*')->from('user')->where('id', 3)->go($id);
        if ($ex['result']) {
            $mysqlPool->commit();
            return true;
        } else {
            $mysqlPool->rollback();
            return false;
        }
    }
}
