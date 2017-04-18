<?php
namespace App\Lib;

use pgc\helpers\ResponseHelper;
use PG\MSF\Server\Controllers\BaseController;

class InnerController extends BaseController
{
    public $requestStartTime = 0.0;

    public function initialization($controller_name, $method_name)
    {
        parent::initialization($controller_name, $method_name);
        $this->PGLog->pushLog('params', $this->input->getAllPostGet());
        return true;
    }
}
