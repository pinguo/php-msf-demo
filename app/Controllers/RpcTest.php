<?php
/**
 * RPC示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use PG\MSF\Client\RpcClient;

class RpcTest extends Controller
{
    public function actionGetSum()
    {
        /**
         * @var RpcClient $client
         */
        $client = $this->getObject(RpcClient::class, ['demo']);
        $sum    = yield $client->handler('sum')->multi(1, 2, 3, 4, 5);
        $this->outputJson($sum);
    }

    public function actionConcurrentSum()
    {
        $rpc[] = $this->getObject(RpcClient::class, ['demo'])->handler('sum')->func('multi', 1, 2, 3, 4, 5);
        $rpc[] = $this->getObject(RpcClient::class, ['demo'])->handler('sum')->func('multi', 1, 2, 3, 4);
        $rpc[] = $this->getObject(RpcClient::class, ['demo'])->handler('sum')->func('multi', 1, 2, 3);
        $rpc[] = $this->getObject(RpcClient::class, ['demo'])->handler('sum')->func('multi', 1, 2);

        $sum = yield RpcClient::goConcurrent($rpc);
        $this->outputJson($sum);
    }
}
