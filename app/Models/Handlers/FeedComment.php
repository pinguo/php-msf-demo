<?php
/**
 * FeedComment
 */
namespace App\Models\Handlers;

use PG\MSF\Models\Model;

class FeedComment extends Model
{
    public function getByFid($fid)
    {
        if (empty($fid)) {
            return [];
        }

        return [
            $fid = [
                [
                    'cid'     => 1,
                    'content' => '好美',
                ],
                [
                    'cid'     => 2,
                    'content' => '身材真棒!',
                ],
                [
                    'cid'     => 3,
                    'content' => '完美',
                ],
            ],
        ];
    }
}