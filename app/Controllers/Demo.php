<?php
/**
 * 示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Client\Http\Client;
use PG\MSF\Controllers\Controller;
use App\Models\Demo as DemoModel;

class Demo extends Controller
{
    public function actionGetMockIds()
    {
        $ids = $this->getConfig()->get('params.mock_ids', []);
        $this->outputJson($ids);
    }

    public function actionGetMockFromModel()
    {
        /**
         * @var DemoModel $demoModel
         */
        $demoModel = $this->getObject(DemoModel::class, [1, 2]);
        $ids       = $demoModel->getMockIds();
        $this->outputJson($ids);
    }

    public function actionTplView()
    {
        $data = [
            'title'    => 'MSF Demo View',
            'friends'  => [
                [
                    'name' => 'Rango',
                ],
                [
                    'name' => '鸟哥',
                ],
                [
                    'name' => '小马哥',
                ],
            ]
        ];
        $this->outputView($data);
    }

    public function actionSleep()
    {
        yield $this->getObject(\PG\MSF\Coroutine\Sleep::class)->goSleep(2000);
        $this->outputJson(['status' => 200, 'msg' => 'ok']);
    }

    public function actionLocalResponse()
    {
        dump($this->getContext()->getInput()->request);
        $this->output('ok');
    }

    public function actionLocalRequest()
    {
        $url = 'http://127.0.0.1:8000/Demo/LocalResponse?a=b';
        yield $this->getObject(Client::class)->goSinglePost($url, ['c' => 'd']);

        $this->output('ok');
    }
}
