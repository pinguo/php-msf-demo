<?php
/**
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 26/10/2017
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use PG\MSF\Queue\Kafka;
use PG\MSF\Queue\RabbitMQ;

class Queue extends Controller
{
    public function actionRedisEnqueue()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->set(json_encode(['name' => 'leandre', 'gender' => 'male']));
        $this->outputJson($res);
    }

    public function actionRedisDequeue()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->get();
        $this->outputJson($res);
    }

    public function actionRedisLen()
    {
        $redisQueue = $this->getObject(\PG\MSF\Queue\Redis::class, ['master_slave', true]);
        $res = yield $redisQueue->len();
        $this->outputJson($res);
    }

    public function actionRabbitEnqueue()
    {
        $rabbit = $this->getObject(RabbitMQ::class, ['rabbit']);
        $res = yield $rabbit->set(json_encode(['name' => 'msf', 'type' => 'framework']));
        $this->outputJson($res);
    }

    public function actionRabbitDequeue()
    {
        $rabbit = $this->getObject(RabbitMQ::class, ['rabbit']);
        $res = yield $rabbit->get();
        $this->outputJson($res);
    }

    public function actionKafkaEnqueue()
    {
        $kafka = $this->getObject(Kafka::class, ['local']);
        $res = yield $kafka->set(json_encode(['name' => 'camera360', 'type' => 'app']));
        $this->outputJson($res);
    }

    public function actionKafkaDequeue()
    {
        $kafka = $this->getObject(Kafka::class, ['local']);
        $res = yield $kafka->get();
        $this->outputJson($res);
    }
}
