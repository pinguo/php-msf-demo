<?php
/**
 *
 * @author lingyun <niulingyun@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 * Date: 25/11/2017
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;

class Session extends Controller
{
    public function actionTest()
    {
        $session = $this->getObject(\PG\MSF\Session\Session::class);
        $data = [];
        $data['old'] = yield $session->get('msf-session');
        yield $session->set('msf-session', date('Y-m-d H:i:s'));
        $data['new'] = yield $session->get('msf-session');

        $this->outputJson($data);
    }
}
