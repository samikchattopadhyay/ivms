<?php

namespace App\Helpers;
 
use Illuminate\Support\Facades\DB;
 
class IvmsNotifier {
    
    public static function countNew($uid) 
    {
        return DB::table('user_notifications as un')
        ->join('notifications as n', 'n.id', '=', 'un.nid')
        ->where([
            'un.uid' => $uid,
            'un.seen' => 0
        ])
        ->select(['n.id'])
        ->count();
    }
    
    /**
     * @param int $user_id User-id
     * 
     * @return string
     */
    public static function getNew($uid, $limit = 15) 
    {
        return DB::table('user_notifications as un')
        ->join('notifications as n', 'n.id', '=', 'un.nid')
        ->where([
            'un.uid' => $uid,
            'un.seen' => 0
        ])
        ->select(['un.id as unid', 'n.message', 'n.target'])
        ->orderBy('n.id', 'desc')
        ->limit($limit)
        ->get();
    }
}
