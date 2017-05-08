<?php
namespace App\Controllers\Api;

use PG\MSF\Controllers\BaseController;

class Pr extends BaseController
{
    public function HttpFeed()
    {
        $data = getInstance()->config->get('params.mock_pr_feed');
        $this->outputJson($data);
    }
}
