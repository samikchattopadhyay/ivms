<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject', 'message', 'target', 'uid'
    ];
    
    public static function add($subject, $message, $recipients, $target = null) 
    {
        if ($recipients) {
            
            $nid = self::create([
                'subject' => $subject,
                'message' => $message,
                'target' => $target,
                'uid' => Auth::user()->id
            ])->id;
            
            if (!is_array($recipients)) {
                $recipients = [$recipients];
            }
            
            if (!empty($nid)) {
                foreach ($recipients as $uid) {
                    UserNotification::create([
                        'uid' => $uid, 
                        'nid' => $nid, 
                    ]);
                }
            }
        }
    }
}
