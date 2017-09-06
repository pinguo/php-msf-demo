<?php
/**
 * 欢迎
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;

class Welcome extends Controller
{
    public function actionIndex()
    {
        $this->output('hello world!');
    }
}
