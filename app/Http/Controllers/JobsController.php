<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\QuestionGroup;
use App\Job;
use App\User;
use App\Notification;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = DB::table('jobs')
        ->select(array(
            'jobs.*',
            DB::raw('COUNT(candidates.id) as cv_count'))
        )
        ->leftJoin('candidates', 'jobs.id', '=', 'candidates.job_id')
        ->groupBy('jobs.id');
        
        return view('jobs.index', [
            'jobs' => $jobs->paginate(10),
            'action' => 'Add New'
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jobs.create', [
            'job' => false,
            'users' => User::get(),
            'qgroups' => QuestionGroup::all(),
            'action' => 'Add New'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'position' => 'required', 
            'vacancies' => 'required|numeric', 
            'description' => 'required', 
            'responsibilities' => 'required', 
            'compensation' => 'required|numeric',
            'expiry_date' => 'required',
            'interviewer_id' => 'required',
            'hr_id' => 'required',
        ]);
        
        // Add the question
        $jid = Job::create([
            'position' => $request['position'],
            'vacancies' => $request['vacancies'],
            'location' => $request['location'],
            'description' => $request['description'],
            'responsibilities' => $request['responsibilities'],
            'compensation' => $request['compensation'],
            'qgroups' => implode(',', $request['qgroups']),
            'expiry_date' => $request['expiry_date'],
            'interviewer_id' => $request['interviewer_id'],
            'hr_id' => $request['hr_id'],
        ])->id;
        
        // Add notifications for interviewer and HR manager
        Notification::add('New job added', "New job '{$request['position']}' has been added. Please take necessary actions.", [
            $request['interviewer_id'],
            $request['hr_id']
        ], route('job.edit', [
            'id' => $jid
        ]));
        
        return redirect()->intended( route('job.index') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'View job details';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        
        // Redirect to user list if updating user wasn't existed
        if (empty($job)) {
            return redirect()->intended('/job');
        }
        
        return view('jobs.create', [
            'job' => $job,
            'users' => User::get(),
            'qgroups' => QuestionGroup::all(),
            'action' => 'Edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Job::findOrFail($id);
        
        $constraints = [
            'position' => 'required',
            'vacancies' => 'required|numeric',
            'description' => 'required',
            'responsibilities' => 'required',
            'compensation' => 'required|numeric',
            'expiry_date' => 'required',
            'interviewer_id' => 'required',
            'hr_id' => 'required',
        ];
        
        $input = [
            'position' => $request['position'],
            'vacancies' => $request['vacancies'],
            'location' => $request['location'],
            'description' => $request['description'],
            'responsibilities' => $request['responsibilities'],
            'compensation' => $request['compensation'],
            'qgroups' => implode(',', $request['qgroups']),
            'expiry_date' => $request['expiry_date'],
            'interviewer_id' => $request['interviewer_id'],
            'hr_id' => $request['hr_id'],
        ];
        
        $this->validate($request, $constraints);
        Job::where('id', $id)
            ->update($input);
        
        return redirect()->intended('/job');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Job::where('id', $id)->delete();
        return redirect()->intended( route('job.index') );
    }
}
