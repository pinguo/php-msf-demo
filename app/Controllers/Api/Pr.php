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
    public function HttpFeed()
    {
        $data = getInstance()->config->get('params.mock_pr_feed');
        $this->outputJson($data);
    }

    /**
     * 获取用户推荐列表
     *
     * @throws \PG\MSF\Base\Exception
     */
    public function HttpRec()
    {
        /**
         * @var $feed Feed
         */
        $feed = $this->getLoader()->model(Feed::class, $this);
        $data = $feed->rec();
        $this->outputJson($data);
    }

    /**
     * 从MongoDB获取一个自增ID
     */
    public function HttpGetId()
    {
        /**
         * @var $idTask Idallloc
         */
        $idTask = $this->getLoader()->task(Idallloc::class, $this);
        $idTask->getNextId(1);
        $id = yield $idTask->coroutineSend();
        $this->outputJson($id);
    }
}
