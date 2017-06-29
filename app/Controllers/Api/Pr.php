<?php
/**
 * 拉取用户推荐列表
 */
namespace App\Controllers\Api;

use PG\MSF\Controllers\Controller;
use App\Models\Feed;
use App\Tasks\Idallloc;

class Pr extends Controller
{

    /**
     * 获取用户Feed
     */
    public function httpFeed()
    {
        // 获取配置数据
        $data = getInstance()->config->get('params.mock_pr_feed');
        // 结束请求并响应json数据格式
        $this->outputJson($data);
    }

    /**
     * 获取用户推荐列表
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpRec()
    {
        /**
         * @var $feed Feed
         */
        // 加载Feed模型
        $feed = $this->getLoader()->model(Feed::class, $this);
        // 执行方法
        $data = $feed->rec();
        // 结束请求并响应json数据格式
        $this->outputJson($data);
    }

    /**
     * 从MongoDB获取一个自增ID
     */
    public function httpGetId()
    {
        /**
         * @var $idTask Idallloc
         */
        // 加载模型
        $idTask = $this->getLoader()->task(Idallloc::class, $this);
        // 拼装调用Idallloc::getNextId()方法
        $idTask->getNextId(1);
        // 将同步任务的执行加入协程调度队列
        $id = yield $idTask->coroutineSend(1000);
        // 结束请求并响应json数据格式
        $this->outputJson($id);
    }

    /**
     * 异步回调的方式实现(A && B) || C
     */
    public function httpCallBackMode()
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
    public function httpCoroutineMode()
    {
        // 从Redis获取get apiCacheForABCoroutine
        $getRedisCoroutine = $this->getRedisPool('tw')->get('apiCacheForABCoroutine');
        $response          = yield $getRedisCoroutine;
        if (!$response) {
            // 并行请求百度和七牛首页
            $requests = [
                // 注册的服务接口名
                'baidu' => [
                ],
                // 注册的服务接口名
                'qiniu' => [
                ],
            ];

            $results = yield \PG\MSF\Client\ConcurrentClient::requestByConcurrent($requests, $this);
            // 写入redis,并不获取服务器返回结果（可大辐提升性能）
            $this->getRedisPool('tw')->set('apiCacheForABCoroutine', $results['baidu']['body'] . $results['qiniu']['body']);
            $response = $results['baidu']['body'] . $results['qiniu']['body'];
        }

        // 响应结果
        $this->outputJson($response);
    }

    /**
     * 获取百度首页,手工进行DNS解析和数据拉取
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpBaiduIndexWithOutDNS()
    {
        /**
         * @var $client \PG\MSF\Client\Http\Client
         */
        $client     = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
        $sendDNS    = $client->coroutineGetHttpClient('http://www.baidu.com');
        /**
         * @var $httpClient \PG\MSF\Client\Http\HttpClient
         */
        $httpClient = yield $sendDNS;
        $sendGet    = $httpClient->coroutineGet('/');
        $result     = yield $sendGet;
        
        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 获取百度首页,自动进行DNS
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpBaiduIndexWithDNS()
    {
        /**
         * @var $client \PG\MSF\Client\Http\Client
         */
        $client     = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
        /**
         * @var $httpClient \PG\MSF\Client\Http\HttpClient
         */
        $httpClient = yield $client->coroutineHttpClientWithDNS('http://www.baidu.com');
        $sendGet    = $httpClient->coroutineGet('/');
        $result     = yield $sendGet;

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 获取百度首页,自动进行DNS,自动通过Get拉取数据
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpBaiduIndexGet()
    {
        /**
         * @var $client \PG\MSF\Client\Http\Client
         */
        $client = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
        $result = yield $client->coroutineGet('http://www.baidu.com/');

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 获取百度首页,自动进行DNS,自动通过Get拉取数据
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpBaiduIndexPost()
    {
        /**
         * @var $client \PG\MSF\Client\Http\Client
         */
        $client = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
        $result = yield $client->coroutinePost('http://www.baidu.com/');

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 在一个请求中多次获取百度首页,自动进行DNS,自动通过Get拉取数据
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpMultipleBaiduIndexGet()
    {
        /**
         * @var $client \PG\MSF\Client\Http\Client
         */
        $client = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
        $res1   = yield $client->coroutineGet('http://www.baidu.com/');
        $res2   = yield $client->coroutineGet('http://www.baidu.com/');

        $this->outputView(['html' => strlen($res1['body']) . '<hr />' . strlen($res2['body'])]);
    }

    /**
     * 并行多次获取百度首页,自动进行DNS,自动通过Get拉取数据
     */
    public function httpConcurrentBaiduIndexGet()
    {
        $requests = [
            'baidu' => [
                'a' => 1,
            ],
            'baidu1' => [
                'a' => 2,
            ],
            'baidu2' => [
                'a' => 3,
            ],
            'baidu3' => [
                'a' => 4,
            ]
        ];

        $results = yield \PG\MSF\Client\ConcurrentClient::requestByConcurrent($requests, $this);

        $this->outputJson($results);
    }

    /**
     * Redis连接池的使用
     */
    public function httpRedisPoolUsage()
    {
        /**
         * 获取名为tw的Redis连接池,并发送set操作指令
         */
        $setKey1 = $this->getRedisPool('tw')->set('key1', 'val1');
        $setKey2 = $this->getRedisPool('tw')->set('key2', 'val2');
        $setKey3 = $this->getRedisPool('tw')->set('key3', 'val3');
        /**
         * 获取名为tw的Redis连接池,并发送get操作指令
         */
        $getKey1 = $this->getRedisPool('tw')->get('key1');
        $getKey2 = $this->getRedisPool('tw')->get('key2');
        $getKey3 = $this->getRedisPool('tw')->get('key3');
        /**
         * 获取set的返回结果
         */
        $ret1    = yield $setKey1;
        $ret2    = yield $setKey2;
        $ret3    = yield $setKey3;
        /**
         * 获取get的返回结果
         */
        $ret4    = yield $getKey1;
        $ret5    = yield $getKey2;
        $ret6    = yield $getKey3;

        if ($ret1 && $ret2 && $ret3 && $ret4 && $ret5 && $ret6) {
            $this->outputJson('ok');
        } else {
            $this->outputJson('error');
        }
    }

    /**
     * Redis代理的使用
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function httpRedisProxyUsage()
    {
        $this->getContext()->getLog()->profileStart('no-yield');
        for ($i = 1; $i < 100; $i++) {
            // 设置100个key到Redis集群,并且不需要获取返回值
            $this->getRedisProxy('cluster')->set('cs-' . $i, $i*$i);
        }
        $this->getContext()->getLog()->profileEnd('no-yield');

        $this->getContext()->getLog()->profileStart('has-yield');
        for ($i = 1; $i < 100; $i++) {
            // 设置100个key到Redis集群,并且需要获取返回值
            $sendSetKey[$i] = $this->getRedisProxy('cluster')->set('cs-' . $i, $i*$i);
        }
        for ($i = 1; $i < 100; $i++) {
            $res[$i] = yield $sendSetKey[$i];
        }
        $this->getContext()->getLog()->profileEnd('has-yield');

        // 设置key为user_id_111,value为name111到主从结构的Redis集群
        $this->getRedisProxy('master_slave')->set('user_id_111', 'name111');

        $this->outputJson('ok');
    }
}
