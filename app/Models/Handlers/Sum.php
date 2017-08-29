<?php
/**
 * RPC示例Handler
 */
namespace App\Models\Handlers;

use PG\MSF\Models\Model;

class Sum extends Model
{
    public $a;

    public function __construct($a = 0)
    {
        $this->a = $a;
    }

    /**
     * 求和
     *
     * @param array ...$args
     * @return float|int
     */
    public function multi(...$args)
    {
        return array_sum($args);
    }
}