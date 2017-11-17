<?php
/**
 * 示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\I18N\I18N;
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

    public function actionI18n()
    {
        // 这个最好在业务控制器基类里的构造方法初始化，这里只作为演示方便
        I18N::getInstance(getInstance()->config->get('params.i18n', []));
        $sayHi = [
            'zh_cn' => I18N::t('demo.common', 'sayHi', ['name' => '品果微服务框架'], 'zh_CN'),
            'en_us' => I18N::t('demo.common', 'sayHi', ['name' => 'msf'], 'en_US'),
        ];

        $this->outputJson(['data' => $sayHi, 'status' => 200, 'msg' => I18N::t('demo.error', 200, [], 'zh_CN')]);
    }

    public function actionShellExec()
    {
        $result = yield $this->getObject(\PG\MSF\Coroutine\Shell::class)->goExec('ps aux | grep msf');
        $this->output($result);
    }
}
