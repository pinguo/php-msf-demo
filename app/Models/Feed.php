<?php
/**
 * Feed模型
 */
namespace App\Models;

use PG\MSF\Models\Model;

class Feed extends Model
{
    public function rec()
    {
        // 读取配置后返回
        return getInstance()->config->get('params.mock_pr_feed');
    }
}