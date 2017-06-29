<?php
/**
 * RPC测试
 */
namespace App\Controllers\Api;

use PG\MSF\Controllers\Controller;
use PG\MSF\Client\RpcClient;

class Rpc extends Controller
{
    public function httpGetCommentForId()
    {
        $comments = yield RpcClient::serv('demo')->handler('FeedComment')->getByFid($this, 123);
        $this->outputJson($comments);
    }

    public function httpTestLoader()
    {
        // 场景1
        $class = '\\';
        new $class();
        // 场景2
        class_exists('\\');
        $this->outputJson('ok');
    }
}
