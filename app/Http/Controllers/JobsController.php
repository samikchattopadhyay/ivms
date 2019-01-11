<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\QuestionGroup;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jobs.index', [
            'jobs' => Job::paginate(10),
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
        ]);
        
        // Add the question
        Job::create([
            'position' => $request['position'],
            'vacancies' => $request['vacancies'],
            'location' => $request['location'],
            'description' => $request['description'],
            'responsibilities' => $request['responsibilities'],
            'compensation' => $request['compensation'],
            'qgroups' => implode(',', $request['qgroups']),
            'expiry_date' => $request['expiry_date'],
        ]);
        
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
        $job = Job::findOrFail($id);
        
        $constraints = [
            'position' => 'required',
            'vacancies' => 'required|numeric',
            'description' => 'required',
            'responsibilities' => 'required',
            'compensation' => 'required|numeric',
            'expiry_date' => 'required',
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
