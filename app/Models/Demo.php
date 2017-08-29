<?php
/**
 * Demo模型
 */
namespace App\Models;

use PG\MSF\Models\Model;

class Demo extends Model
{
    public $pro1;
    public $pro2;

    protected $pro3;
    private $pro4;

    public function __construct($pro1, $pro2)
    {
        parent::__construct();
        $this->pro1 = $pro1;
        $this->pro2 = $pro2;
        $this->pro3 = "protected";
        $this->pro2 = "private";
    }

    public function getMockIds()
    {
        // 读取配置后返回
        return $this->getConfig()->get('params.mock_ids', []);
    }

    public function destroy()
    {
        parent::destroy();
        $this->pro3 = null;
    }
}