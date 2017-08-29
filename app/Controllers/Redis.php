<?php
/**
 * Redis示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use App\Models\Demo as DemoModel;

class Redis extends Controller
{
    // Redis连接池读写示例
    public function actionPoolSetGet()
    {
        yield $this->getRedisPool('p1')->set('key1', 'val1');
        $val = yield $this->getRedisPool('p1')->get('key1');

        $this->outputJson($val);
    }

    // Redis代理使用示例（分布式）
    public function actionProxySetGet()
    {
        for ($i = 0; $i <= 100; $i++) {
            yield $this->getRedisProxy('cluster')->set('proxy' . $i, $i);
        }

        $val = yield $this->getRedisProxy('cluster')->get('proxy22');
        $this->outputJson($val);
    }

    // Redis代理使用示例（主从）
    public function actionMaserSlaveSetGet()
    {
        for ($i = 0; $i <= 100; $i++) {
            yield $this->getRedisProxy('master_slave')->set('M' . $i, $i);
        }

        $val = yield $this->getRedisProxy('master_slave')->get('M66');
        $this->outputJson($val);
    }
}
