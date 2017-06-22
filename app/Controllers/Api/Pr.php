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
            // 从对象池中获取Http Client
            $client     = $this->getContext()->getObjectPool()->get(\PG\MSF\Client\Http\Client::class);
            // 异步dns解析
            $dnsACor    = $client->coroutineGetHttpClient('https://www.baidu.com');
            $dnsBCor    = $client->coroutineGetHttpClient('https://www.qiniu.com');
            // 获取dns解析的结果
            $httClientA = yield $dnsACor;
            $httClientB = yield $dnsBCor;
            // 异步拉取数据
            $resultACor = $httClientA->coroutineGet('/');
            $resultBCor = $httClientB->coroutineGet('/');
            // 获取拉取数据的结果
            $resultA    = yield $resultACor;
            $resultB    = yield $resultBCor;
            // 写入redis
            $setRedisCoroutine = $this->getRedisPool('tw')->set('apiCacheForABCoroutine', $resultA['body'] . $resultB['body']);
            yield $setRedisCoroutine;
            $response   = $resultA['body'] . $resultB['body'];
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

    }
}
