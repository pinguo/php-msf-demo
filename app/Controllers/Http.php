<?php
/**
 * 异步HTTP CLIENT示例
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use \PG\MSF\Client\Http\Client;

class Http extends Controller
{
    /**
     * 获取百度首页,手工进行DNS解析和数据拉取
     */
    public function actionBaiduIndexWithOutDNS()
    {
        /**
         * @var Client $client
         */
        $client  = $this->getObject(Client::class);
        yield $client->goDnsLookup('http://www.baidu.com');

        $sendGet = $client->goGet('/');
        $result  = yield $sendGet;

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 获取百度首页,自动进行DNS,自动通过Get拉取数据
     */
    public function actionBaiduIndexGet()
    {
        /**
         * @var Client $client
         */
        $client  = $this->getObject(Client::class);
        $result = yield $client->goSingleGet('http://www.baidu.com/');

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 获取百度首页,自动进行DNS,自动通过Post拉取数据
     */
    public function actionBaiduIndexPost()
    {
        /**
         * @var Client $client
         */
        $client  = $this->getObject(Client::class);
        $result = yield $client->goSinglePost('http://www.baidu.com/');

        $this->outputView(['html' => $result['body']]);
    }

    /**
     * 并行多次获取百度首页,自动进行DNS,自动通过Get或者Post拉取数据
     */
    public function actionConcurrentBaiduIndex()
    {
        /**
         * @var Client $client
         */
        $client   = $this->getObject(Client::class);
        $requests = [
            'http://www.baidu.com/',
            [
                'url'    => 'http://www.baidu.com/',
                'method' => 'POST'
            ],
        ];

        $results = yield $client->goConcurrent($requests);

        $this->outputView(['html' => $results[0]['body'] . $results[0]['body']]);
    }
}
