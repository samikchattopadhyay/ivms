<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'position', 'description', 
        'responsibilities', 'compensation', 
        'vacancies', 'location', 'qgroups',
        'expiry_date', 'interviewer_id', 
        'hr_id'
    ];
}
