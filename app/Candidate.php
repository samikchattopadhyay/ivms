<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'location', 'source', 
        'job_id', 'notice_period', 'cv_file',
        'keywords', 'cv_keywords', 'cv_text',
        'cv_match_percent', 'qsent'
    ];
}
