<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cid', 'uid', 'comment'
    ];
}
