<?php
/**
 * MongoDB操作示例
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */

namespace App\Controllers;

use PG\MSF\Controllers\Controller;
use App\Tasks\Idallloc;

class MongoDBTest extends Controller
{
    public function actionGetNewId()
    {
        /**
         * @var Idallloc $idAlloc
         */
        $idAlloc = $this->getObject(Idallloc::class);
        $newId   = yield $idAlloc->getNextId('test');
        $this->outputJson($newId);
    }
}
