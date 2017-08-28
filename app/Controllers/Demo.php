<?php
/**
 * 示例控制器
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;

class Demo extends Controller
{
    public function actionGetMockIds()
    {
        $ids = $this->getConfig()->get('params.mock_ids', []);
        $this->outputJson($ids);
    }
}
