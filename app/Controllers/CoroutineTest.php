<?php
/**
 * 协程示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use PG\MSF\Client\Http\Client;

class CoroutineTest extends Controller
{
    /**
     * 异步回调的方式实现(A && B) || C
     */
    public function actionCallBackMode()
    {
        $client = new \swoole_redis;
        $client->connect('127.0.0.1', 6379, function (\swoole_redis $client, $result) {
            $client->get('apiCacheForABCallBack', function (\swoole_redis $client, $result) {
                if (!$result) {
                    swoole_async_dns_lookup("www.baidu.com", function($host, $ip) use ($client) {
                        $cli = new \swoole_http_client($ip, 443, true);
                        $cli->setHeaders([
                            'Host' => $host,
                        ]);
                        $apiA = "";
                        $cli->get('/', function ($cli) use ($client, $apiA) {
                            $apiA = $cli->body;
                            swoole_async_dns_lookup("www.qiniu.com", function($host, $ip) use ($client, $apiA) {
                                $cli = new \swoole_http_client($ip, 443, true);
                                $cli->setHeaders([
                                    'Host' => $host,
                                ]);
                                $apiB = "";
                                $cli->get('/', function ($cli) use ($client, $apiA, $apiB) {
                                    $apiB = $cli->body;
                                    if ($apiA && $apiB) {
                                        $client->set('apiCacheForABCallBack', $apiA . $apiB, function (\swoole_redis $client, $result) {});
                                        $this->outputJson($apiA . $apiB);
                                    } else {
                                        $this->outputJson('', 'error');
                                    }
                                });
                            });
                        });
                    });
                } else {
                    $this->outputJson($result);
                }
            });
        });
    }

    /**
     * 协程的方式实现(A && B) || C
     */
    public function actionCoroutineMode()
    {
        // 从Redis获取get apiCacheForABCoroutine
        $response = yield $this->getRedisPool('p1')->get('apiCacheForABCoroutine');

        if (!$response) {
            // 从远程拉取数据
            $request = [
                'https://www.baidu.com/',
                'https://www.qiniu.com/',
            ];

            /**
             * @var Client $client
             */
            $client  = $this->getObject(Client::class);
            $results = yield $client->goConcurrent($request);

            // 写入redis
            $this->getRedisPool('p1')->set('apiCacheForABCoroutine', $results[0]['body'] . $results[1]['body'])->break();
            $response   = $results[0]['body'] . $results[1]['body'];
        }

        // 响应结果
        $this->output($response);
    }

    // 姿势一
    public function actionNested()
    {
        $result = [];
        /**
         * @var Client $client1
         */
        $client1 = $this->getObject(Client::class, ['http://www.baidu.com/']);
        yield $client1->goDnsLookup();
        /**
         * @var Client $client2
         */
        $client2 = $this->getObject(Client::class, ['http://www.qq.com/']);
        yield $client2->goDnsLookup();

        $result[] = yield $client1->goGet('/');
        $result[] = yield $client2->goGet('/');

        $this->outputJson([strlen($result[0]['body']), strlen($result[1]['body'])]);
    }

    // 姿势二
    public function actionUnNested()
    {
        $result = [];
        /**
         * @var Client $client1
         */
        $client1 = $this->getObject(Client::class, ['http://www.baidu.com/']);
        $dns[]   = $client1->goDnsLookup();
        /**
         * @var Client $client2
         */
        $client2 = $this->getObject(Client::class, ['http://www.qq.com/']);
        $dns[]   = $client2->goDnsLookup();

        yield $dns[0];
        yield $dns[1];

        $req[] = $client1->goGet('/');
        $req[] = $client2->goGet('/');

        $result[] = yield $req[0];
        $result[] = yield $req[1];

        $this->outputJson([strlen($result[0]['body']), strlen($result[1]['body'])]);
    }
}
