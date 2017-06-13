<?php
namespace App\Tasks;

use \PG\MSF\Tasks\MongoDbTask;

class Idallloc extends MongoDbTask
{
    /**
     * 当前要用的配置  配置名，db名，collection名
     * @var array
     */
    public $mongoConf = ['hotpot', 'hotpot', 'idalloc'];
    
    public function getNextId($key)
    {
        $condition = [
            '_id' => $key,
        ];
        $update = [
            '$inc' => [
                'last' => 1,
            ],
        ];
        $options = [
            'new' => true,
            'upsert' => true,
        ];


        $doc = $this->mongoCollection->findAndModify($condition, $update, [], $options);
        
        return isset($doc['last']) ? $doc['last'] : false;
    }
}
