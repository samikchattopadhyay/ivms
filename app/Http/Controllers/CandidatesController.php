<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Candidate;
use App\Job;
use App\Question;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Option;


class CandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Candidate::paginate(10)
        $candidates = DB::table('candidates')
            ->leftJoin('jobs', 'candidates.job_id', '=', 'jobs.id')
            ->select('candidates.*', 'jobs.position as job_position')
            ->orderBy('candidates.id', 'desc')
            ->paginate(10);
        
        return view('candidates.index', [
            'candidates' => $candidates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('candidates.create', [
            'jobs' => Job::all(),
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
            'name' => 'required|max:100',
            'email' => 'required|email|max:255|unique:candidates',
            'job_id' => 'required',
            'notice_period' => 'required',
            'cv_file' => 'required|max:10000|mimes:doc,docx,pdf',
            'cv_text' => 'required',
        ]);
        
        $cvText = htmlentities(mb_convert_encoding($request['cv_text'], "UTF-8"));
        
        $study = $this->studyTheCv($cvText, $request['job_id']);
        
        $inputs = [
            'name' => $request['name'],
            'email' => $request['email'],
            'job_id' => $request['job_id'],
            'location' => $request['location'],
            'source' => $request['source'],
            'notice_period' => $request['notice_period'],
            'keywords' => $request['keywords'],
            'cv_file' => $request->file('cv_file')->store('cv'),
            'cv_keywords' => isset($study['found']) ? $study['found'] : '',
            'cv_match_percent' => isset($study['match']) ? $study['match'] : 0,
        ];
        
        $id = Candidate::create($inputs)->id;
        
        Storage::put('cv/txt/' . $id . '.txt', $cvText);
        
        return redirect()->intended('/candidate');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'View Candidate details';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        
        // Read CV text
        try {
            $candidate->cv_text = Storage::get('cv/txt/' . $id . '.txt');
        } catch (FileNotFoundException $e) {
            $candidate->cv_text = '';
        }
        
        // Redirect to user list if updating user wasn't existed
        if (empty($candidate)) {
            return redirect()->intended('/candidate');
        }
        
        return view('candidates.create', [
            'jobs' => Job::all(),
            'candidate' => $candidate,
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
        $constraints = [
            'name' => 'required|max:100',
            'job_id' => 'required',
            'notice_period' => 'required',
            'cv_file' => 'max:10000|mimes:doc,docx,pdf',
        ];
        
        $cvText = htmlentities(mb_convert_encoding($request['cv_text'], "UTF-8"));
        $study = $this->studyTheCv($cvText, $request['job_id']);
        
        $input = [
            'name' => $request['name'],
            'job_id' => $request['job_id'],
            'location' => $request['location'],
            'source' => $request['source'],
            'notice_period' => $request['notice_period'],
            'keywords' => $request['keywords'],
            'cv_keywords' => isset($study['found']) ? $study['found'] : '',
            'cv_match_percent' => isset($study['match']) ? $study['match'] : 0,
        ];
        
        // Upload CV
        if (!empty($request->file('cv_file'))) {
            $input['cv_file'] = $request->file('cv_file')->store('cv');
            Storage::put('cv/txt/' . $id . '.txt', $cvText);
        }
        
        $this->validate($request, $constraints);
        Candidate::where('id', $id)
            ->update($input);
        
        return redirect()->intended('/candidate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Candidate::where('id', $id)->delete();
        return redirect()->intended('/candidate');
    }
    
    public function recalc(Request $request) 
    {
        $candidate = Candidate::findOrFail($request->id);
        $candidate->cv_text = Storage::get('cv/txt/' . $request->id . '.txt');
        $study = $this->studyTheCv($candidate->cv_text, $candidate->job_id);
        Candidate::where('id', $request->id)
        ->update([
            'cv_keywords' => isset($study['found']) ? $study['found'] : '',
            'cv_match_percent' => isset($study['match']) ? $study['match'] : 0,
        ]);
        
        return redirect()->intended('/candidate');
    }
    
    public function qset(Request $request)
    {
        $candidate = Candidate::findOrFail($request->id);
        $candidate->cv_text = Storage::get('cv/txt/' . $request->id . '.txt');
        $questions = $this->studyTheCvAndGetQuestions($candidate->cv_text, $candidate->job_id);
        
        return view('candidates.qset', [
            'questions' => $questions
        ]);
        
    }
    
    private function studyTheCv($cvText, $jobId) 
    {
        $cvText = trim($cvText);
        
        if (empty($cvText)) {
            return false;
        }
        
        $found = array();
        $keywords = array();
        
        $job = Job::where('id', $jobId)->select('qgroups')->first();
        $questions = Question::whereIn('gid', explode(',', $job->qgroups))
            ->whereNotNull('keywords')
            ->select('keywords')
            ->get();
    
        foreach ($questions as $question) {
            $keywords[] = $question->keywords;
        }
        
        $keywords = implode(',', $keywords);
        $keywords = array_unique(array_filter(explode(',', $keywords)));
        
        foreach ($keywords as $keyword) {
            if (stristr($cvText, $keyword) !== false) {
                $found[] = $keyword;
            }
        }
        
        if (count($keywords)) {
            $match = (count($found) / count($keywords)) * 100;
        }
        
        return array(
            'found' => implode(',', $found),
            'match' => $match
        );
    }
    
    private function studyTheCvAndGetQuestions($cvText, $jobId)
    {
        $cvText = trim($cvText);
        
        if (empty($cvText)) {
            return false;
        }
        
        $selectedQuestions = array();
        
        $job = Job::where('id', $jobId)->select('qgroups')->first();
        $questions = Question::whereIn('gid', explode(',', $job->qgroups))
            ->whereNotNull('keywords')
            ->get();
        
        foreach ($questions as $question) {
            
            $keywords = $question->keywords;
            $keywords = array_unique(array_filter(explode(',', $keywords)));
            
            foreach ($keywords as $keyword) {
                if (stristr($cvText, $keyword) !== false) {
                    if ( in_array($question->type, ['Select', 'Check']) ) {
                        $question->options = Option::where('qid', $question->id)->get();
                    }
                    $selectedQuestions[] = $question;
                    break;
                }
            }
        }
        
        return $selectedQuestions;
    }
    
}
