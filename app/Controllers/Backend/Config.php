<?php
/**
 * 示例后台管理接口控制器
 *
 * @author xudianyang<xudianyang@camera360.com>
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
namespace App\Controllers\Backend;

use App\Controllers\InnerController;

class Config extends InnerController
{
    public function HttpGet()
    {
        $this->outputJson('ok');
    }
}
