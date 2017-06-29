<?php
/**
 * PlanA
 */
namespace App\Models\Handles;

use \PG\AOP\MI;

class PlanA
{
    use MI;
    public function getPlanName()
    {
        return 'PlanA';
    }
}