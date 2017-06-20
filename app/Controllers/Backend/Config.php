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
    public function httpGet()
    {
        // 结束请求并响应json数据格式
        $this->outputJson('ok');
    }

    public function httpPage()
    {
        // 结束请求并响应HTML视图数据
        $this->outputView([
            'name'    => '示例页面',
            'friends' => [
                [
                    'id'   => 1,
                    'name' => '小牛',
                ],
                [
                    'id'   => 2,
                    'name' => '小王',
                ],
                [
                    'id'   => 3,
                    'name' => '小李',
                ],
                [
                    'id'   => 4,
                    'name' => '小徐',
                ],
            ]
        ]);
    }
}
