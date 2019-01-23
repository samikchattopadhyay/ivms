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
        'mobile', 'cv_keywords', 'cv_text',
        'cv_match_percent', 'qsent', 'status',
        'interview'
    ];
    
    public static $statusList = array(
        'NEW' => 'New',
        'REJ' => 'Rejected',
        'SLT' => 'Shortlisted',
        'QNA' => 'Q&A Pending',
        'INV' => 'Interview',
        'WTG' => 'Waiting',
        'SEL' => 'Selected',
        'NEG' => 'Negotiate',
        'CNF' => 'Confirmed',
        'JND' => 'Joined',
    );
    
    public static $statusComments = array(
        'REJ' => 'Candidate has been rejected.',
        'SLT' => 'Candidate has been shortlisted. Need to send Q&A.',
        'QNA' => 'Q&A set has been sent to this candidate.',
        'INV' => 'Need to validate the Q&A and take interview if necessary.',
        'WTG' => 'Stored in a waiting list, will considered later.',
        'SEL' => 'This candidate has been selected after Q&A test and interview.',
        'NEG' => 'Need to negotiate with this candidate.',
        'CNF' => 'Candidature has been confirmed.',
        'JND' => 'Joined our organisation.',
    );
}
