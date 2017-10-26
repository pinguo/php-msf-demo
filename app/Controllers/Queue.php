<?php
/**
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 26/10/2017
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;

class Queue extends Controller
{
    public function actionRedisEnqueue()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->set('queue1', json_encode(['name' => 'leandre', 'gender' => 'male']));
        $this->outputJson($res);
    }

    public function actionRedisDequeue()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->get('queue1');
        $this->outputJson($res);
    }

    public function actionRedisLen()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->len('queue1');
        $this->outputJson($res);
    }
}
