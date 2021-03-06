<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Candidate;
use App\Job;
use App\Question;
use App\QuestionAnswer;
use File;
use Response;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Option;
use App\CandidateComment;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App\Notification;
use App\Helpers\IvmsTextExtractor;

class CandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rpp = 50;
        
        // Candidate::paginate(10)
        $results = DB::table('candidates')
        ->leftJoin('jobs', 'candidates.job_id', '=', 'jobs.id')
        ->leftJoin('candidate_comments as cc', 'cc.cid', '=', 'candidates.id' )
        ->select('candidates.*', DB::raw('count(cc.id) as comments'), 'jobs.position as job_position')
        ->groupBy('candidates.id')
        ->orderBy('candidates.id', 'desc');
        
        if (!empty($request->jid)) {
            $rpp = 1000;
            $results->where('job_id', $request->jid);
            $job = Job::find($request->jid);
        }
        
        if (!empty($request->s)) {
            $rpp = 1000;
            $results->where('name', 'like', '%' . $request->s . '%');
        }
        
        if (!empty($request->t)) {
            $rpp = 1000;
            $results->where('status', '=', $request->t);
        }
        
        $candidates = $results->paginate($rpp);
        
        return view('candidates.index', [
            'job' => isset($job) ? $job : '',
            'candidates' => $candidates,
            'statusList' => Candidate::$statusList
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
            'mobile' => 'required',
            'cv_file' => 'required|max:10000|mimes:docx,pdf',
        ]);
        
        $uploadedCv = $request->file('cv_file')->store('cv');
        $uploadedCvPath = storage_path('app/' . $uploadedCv); 
        $cvText = trim(IvmsTextExtractor::extract($uploadedCvPath));
        
        if (empty($cvText)) {
            return redirect()->back()->with('message', [
                'type' => 'error', 
                'text' => 'CV could not be read. Please upload a valid PDF file.'
            ]);
        }
        
        $study = $this->studyTheCv($cvText, $request['job_id']);
        if ($study['match'] == 0) {
            return back()->with('status', [
                'type' => 'danger',
                'msg' => 'CV does not match any keywords required for this job. '
            ]);
        }
        
        $inputs = [
            'name' => $request['name'],
            'email' => $request['email'],
            'job_id' => $request['job_id'],
            'location' => $request['location'],
            'source' => $request['source'],
            'notice_period' => $request['notice_period'],
            'mobile' => $request['mobile'],
            'cv_file' => $uploadedCv,
            'cv_keywords' => isset($study['found']) ? $study['found'] : '',
            'cv_match_percent' => isset($study['match']) ? $study['match'] : 0,
        ];
        
        // Create new candidate
        $cid = Candidate::create($inputs)->id;
        
        // Store the extracted CV text
        Storage::put('cv/txt/' . $cid . '.txt', $cvText);
        
        // Get candidates job details
        $job = Job::find($request['job_id']);
        
        // Add notifications for interviewer and HR manager
        Notification::add('New candidate added', "New candidate '{$request['name']}' has been added for job '{$job->position}'.", [
            $job->interviewer_id,
            $job->hr_id
        ], "/candidates/preview/{$cid}/ex");
        
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
            'mobile' => 'required',
            'cv_file' => 'max:10000|mimes:docx,pdf',
        ];
        
        // Prepare DB update
        $input = [
            'name' => $request['name'],
            'job_id' => $request['job_id'],
            'location' => $request['location'],
            'source' => $request['source'],
            'notice_period' => $request['notice_period'],
            'mobile' => $request['mobile'],
        ];
        
        // Save the CV if uploaded via form
        if (!empty($request->file('cv_file'))) {
            
            // Upload
            $input['cv_file'] = $uploadedCvPath = $request->file('cv_file')->store('cv');
            
            // Extract text
            $uploadedCvPath = storage_path('app/' . $uploadedCvPath);
            $cvText = trim(IvmsTextExtractor::extract($uploadedCvPath));
            
            if (empty($cvText)) {
                return redirect()->back()->with('message', [
                    'type' => 'error',
                    'text' => 'CV could not be read. Please upload a valid PDF file.'
                ]);
            }
            
            // Study the CV
            $study = $this->studyTheCv($cvText, $request['job_id']);
            
            Storage::put('cv/txt/' . $id . '.txt', $cvText);
        }
        
        // Update the study results
        if (isset($study)) {
            $input['cv_keywords'] = isset($study['found']) ? $study['found'] : '';
            $input['cv_match_percent'] = isset($study['match']) ? $study['match'] : 0;
        }
        
        // Validate
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

    /**
     * Recalculate match percent after CV gets updated
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recalc(Request $request) 
    {
        $candidate = Candidate::findOrFail($request->id);
        
        $cvReadError = false;
        
        try {
            $cvText = trim(Storage::get('cv/txt/' . $request->id . '.txt'));
        } catch(\Exception $e) {
            $cvReadError = true;
        }
        
        if (empty($cvText)) {
            $cvReadError = true;
        }
        
        // If CV could not be read
        // then try to read the file
        if ($cvReadError) {
            
            // Find the uploaded CV file
            $uploadedCvPath = $candidate->cv_file;
            
            // Extract text from the CV
            $uploadedCvPath = storage_path('app/' . $uploadedCvPath);
            
            // Check CV file exists or not
            // If not found then dislay proper error message
            if (!file_exists($uploadedCvPath)) {
                return redirect()->back()->with('message', [
                    'type' => 'error',
                    'text' => 'CV file could not be found. Please upload a proper (.docx/.pdf) CV file.'
                ]);
            }
            
            $cvText = trim(IvmsTextExtractor::extract($uploadedCvPath));
            if (empty($cvText)) {
                return redirect()->back()->with('message', [
                    'type' => 'error',
                    'text' => 'CV file could not be read. Please upload a valid PDF file.'
                ]);
            }
            
            // Re-save the CV text file
            Storage::put('cv/txt/' . $request->id . '.txt', $cvText);
        }
        
        // Study the CV text
        $study = $this->studyTheCv($cvText, $candidate->job_id);
        Candidate::where('id', $request->id)
        ->update([
            'cv_keywords' => isset($study['found']) ? $study['found'] : '',
            'cv_match_percent' => isset($study['match']) ? $study['match'] : 0,
        ]);
        
        return redirect()->intended('/candidate');
    }

    /**
     * List of comments given to a candidate
     * 
     * @param integer $cid
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function comments($cid) {
        return view('candidates.comments', [
            'comments' => CandidateComment::where('cid', $cid)
                ->leftJoin('users', 'users.id', '=', 'candidate_comments.uid')
                ->select(['candidate_comments.*', 'users.name as username'])
                ->get()
        ]);
    }
    
    /**
     * Save a new comment given to a candidate
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request) 
    {
        $id = CandidateComment::create([
            'comment' => $request['comment'],
            'cid' => $request['candidate'],
            'uid' => Auth::user()->id
        ])->id;
        
        $created = $username = '';
        
        if ($id) {
            
            $comment = CandidateComment::where('candidate_comments.id', $id)
            ->leftJoin('users', 'users.id', '=', 'candidate_comments.uid')
            ->select(['candidate_comments.*', 'users.name as username'])
            ->first();
            
            $created = $comment->created_at->diffForHumans();
            $username = $comment->username;
        }
        
        return response()->json([
            'success' => !empty($id),
            'comment' => $request['comment'],
            'created' => $created,
            'username' => $username
        ]);
    }
    
    /**
     * Preview of the candiate details
     * 
     * @param integer $cid
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function preview($cid, $ex = false) 
    {
        $candidate = Candidate::where('candidates.id', $cid)
        ->leftJoin('jobs', 'jobs.id', '=', 'candidates.job_id')
        ->select(['candidates.*', 'jobs.position'])
        ->firstOrFail();
        
        try {
            $candidate->cv_text = Storage::get('cv/txt/' . $cid . '.txt');
        } catch (\Exception $e) {
            $candidate->cv_text = 'CV text file not found';
        }
        
        $study = $this->studyTheCv($candidate->cv_text, $candidate->job_id);
        $questions = $this->studyTheCvAndGetQuestions($candidate->cv_text, $candidate->job_id);
        
        $comments = CandidateComment::where('cid', $cid)
        ->leftJoin('users', 'users.id', '=', 'candidate_comments.uid')
        ->select(['candidate_comments.*', 'users.name as username'])
        ->orderBy('id', 'desc')
        ->get();
        
        $qanda = QuestionAnswer::where('cid', $candidate->id)
        ->leftJoin('questions', 'questions.id', '=', 'question_answers.qid')
        ->select('questions.*', 'question_answers.*')
        ->get();
        
        $answers = array();
        foreach ($qanda as $answer) {
            if ($answer->type != 'Text') {
                $answers[$answer->qid] = explode(',', $answer->answer);
            } else {
                $answers[$answer->qid] = $answer->answer;
            }
        }
        
        return view('candidates.preview', [
            'layout' => empty($ex),
            'candidate' => $candidate,
            'questions' => $questions,
            'answers' => $answers,
            'comments' => $comments,
            'statusList' => Candidate::$statusList,
            'keywords' => isset($study['found']) ? explode(',', $study['found']) : array()
        ]);
    }

    public function status(Request $request) 
    {
        $res = false;
        $cid = Input::get('cid', false);
        $stat = Input::get('status', false);
        
        if ($cid && $stat) {
            
            $res = Candidate::findOrFail($cid)
            ->update(['status' => $stat]);
            
            // Write a comment of this event for the candidate
            if ($res) {
                
                // Get candidate's job ID
                $candidate = Candidate::find($cid);
                
                // Get job details
                $job = Job::find($candidate->job_id);
                
                // Add notifications
                if ($stat == 'SLT') {
                    $subject = 'Candidate shortlisted';
                    $message = "{$candidate->name} has been shortlisted. Need to send Question set.";
                } elseif ($stat == 'SEL') {
                    $subject = 'Candidate selected';
                    $message = "{$candidate->name} has been selected. Need to negotiate.";
                }
                
                if (!empty($subject)) {
                    Notification::add($subject, $message, [
                        $job->interviewer_id,
                        $job->hr_id
                    ], "/candidates/preview/{$cid}/ex");
                }
                
                // Create auto comment for the candidate
                CandidateComment::create([
                    'cid' => $cid,
                    'uid' => Auth::user()->id,
                    'comment' => Candidate::$statusComments[$stat]
                ]);
            }
        }
        
        return response()->json([
            'success' => $res
        ]); 
    }
    
    public function interview() 
    {
        $res = false;
        $cid = Input::get('cid', false);
        $inv = Input::get('interview', false);
        
        if (!empty($cid) && !empty($inv)) {
            
            $invTime = date('Y-m-d H:i:s', strtotime($inv));
            
            $res = Candidate::findOrFail($cid)
            ->update(['interview' => $invTime]);
            
            // Write a comment of this event for the candidate
            if ($res) {
                
                $candidate = Candidate::find($cid);
                
                // Get job details
                $job = Job::find($candidate->job_id);
                
                // Add notifications for interviewer and HR manager
                Notification::add('Interview scheduled', "Interview has been scheduled at " . date('dS M, Y - h:i a', strtotime($invTime)), [
                    $job->interviewer_id,
                    $job->hr_id
                ], "/candidates/preview/{$cid}/ex");
                
                CandidateComment::create([
                    'cid' => $cid,
                    'uid' => Auth::user()->id,
                    'comment' => 'Interview scheduled at ' . date('dS M, Y - h:i a', strtotime($invTime))
                ]);
            }
        }
        
        return response()->json([
            'success' => $res
        ]); 
    }
    
    /**
     * Download resume/cv resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function load($cid) 
    {
        $candidate = Candidate::findOrFail($cid);
        $path = storage_path().'/app/' . $candidate->cv_file;
        
        if (file_exists($path)) {
            $fileName = str_replace(' ', '_', $candidate->name) . '.' . pathinfo($path)['extension'];
            return Response::download($path, $fileName);
        } else {
            die('CV file not found');
        }
    }
    
    /**
     * Send email to candidate having a
     * unique session of the question set
     * prepared for him
     *
     * @param integer $cid
     */
    public function emailQset($cid)
    {
        // Get candidate email
        $candidate = Candidate::findOrFail($cid);
        
        // Generate a unique session ID
        $uqSessId = md5(uniqid(rand(), true)) . md5($cid) . md5(uniqid(rand(), true));
        
        // Prepare the Unique session URL
        $qsetUrl = route('candidates.qset', [
            'session' => $uqSessId,
        ]);
        
        // Set unique session ID for that candidate in database
        // and mark as qset sent
        Candidate::where('id', $cid)
        ->update([
            'uqsessid' => $uqSessId,
            'status' => 'QNA',
            'qsent' => 1
        ]);
        
        // Comment - Question set has been emailed
        CandidateComment::create([
            'cid' => $cid,
            'uid' => Auth::user()->id,
            'comment' => Candidate::$statusComments['QNA']
        ]);
        
        // Get job details
        $job = Job::find($candidate->job_id);
        
        // Add notifications for interviewer and HR manager
        Notification::add('Question set emailed', "Question set has been emailed to {$candidate->name} ({$candidate->email}).", [
            $job->interviewer_id,
            $job->hr_id
        ], $qsetUrl);
        
        // Send email
        try {
            Mail::to($candidate->email)
            ->send(new SendEmail([
                'subject' => 'CV shortlisted - Please submit further details',
                'template' => 'emails.qset',
                'url' => $qsetUrl,
                'candidate' => $candidate,
                'job' => Job::find($candidate->job_id),
                'header_img' => env('MAIL_HEADER_IMAGE', '/eamil-header.png'),
                'company_name' => env('COMPANY_NAME', 'Yourcompany Inc'),
            ]));
        } catch(\Exception $e) {}
        
        return back()->withInput();
    }
    
    /**
     * Public link to access list of questions
     * for a candidate. He will submit the form
     * after he gives answers to the questions
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|string
     */
    public function qset($sessionId) 
    {
        $candidate = Candidate::where('candidates.uqsessid', $sessionId)
        ->leftJoin('jobs', 'jobs.id', '=', 'candidates.job_id')
        ->select(['candidates.*', 'jobs.*'])
        ->firstOrFail();
        
        $questions = Question::whereIn('gid', explode(',', $candidate->qgroups))->get();
        foreach ($questions as $key => $question) {
            $questions[$key]->options = Option::where('qid', $question->id)->get();
        }
        
        return view('candidates.qset', [
            'session' => $sessionId,
            'questions' => $questions,
            'candidate' => $candidate,
            'keywords' => explode(',', $candidate->cv_keywords),
        ]);
    }
    
    /**
     * This is another public resource
     * to store candidate Answers submitted by the qset form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function qsetAnswer(Request $request) 
    {
        $answers = [];
        
        // Get candidate details
        $candidate = Candidate::where('uqsessid', $request->session)->firstOrFail();
        
        // Destroy the Q & A session
        Candidate::where('id', $candidate->id)
        ->update([
            'uqsessid' => NULL,
            'status' => 'INV',
        ]);
        
        // Prepare answers
        foreach ($request->qid as $qid => $answer) {
            
            if (is_array($answer)) {
                $answer = implode(',', $answer);
            }
            
            $answers[] = array(
                'qid' => $qid,
                'cid' => $candidate->id,
                'answer' => $answer
            );
        }
        
        // Save the answers
        QuestionAnswer::insert($answers);
        
        // Get job details
        $job = Job::find($candidate->job_id);
        
        // Add notifications for interviewer and HR manager
        Notification::add('Q&A submitted', "Answer sheet has been submitted by " . $candidate->name . '. Please schedule interview.', [
            $job->interviewer_id,
            $job->hr_id
        ], "/candidates/preview/{$candidate->id}/ex");
        
        // Comment - Answers submitted by the candidate
        CandidateComment::create([
            'cid' => $candidate->id,
            'uid' => 0,
            'comment' => 'Answers submitted by the candidate. ' . Candidate::$statusComments['INV']
        ]);
        
        return view('candidates.answer', [
            'name' => $candidate->name,
        ]);
    }
    
    /**
     * Email test service
     */
    public function testEmail()
    {
        $data = [
            'subject' => 'Testing email',
            'message' => 'Hello this is another test email'
        ];
        Mail::to('samikchattopadhyay@gmail.com')->send(new SendEmail($data));
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
        
        $options = array();
        //$options = $this->getQuestionOptions($jobId);
        $extendedKeywords = array_merge($keywords, $options);
        
        foreach ($extendedKeywords as $keyword) {
            $subkeywords = explode('|', $keyword);
            foreach ($subkeywords as $skeyword) {
                if (stristr($cvText, $skeyword) !== false) {
                    $found[] = $keyword;
                }
            }
        }
        
        if (count($keywords)) {
            $match = (count($found) / count($keywords)) * 100;
            $match = $match > 100 ? 100 : $match;
        }
        
        return array(
            'found' => implode(',', $found),
            'match' => $match,
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
            
            $options = $this->getQuestionOptions($jobId);
            $extendedKeywords = array_merge($keywords, $options);
            
            foreach ($extendedKeywords as $keyword) {
                $subkeywords = explode('|', $keyword);
                foreach ($subkeywords as $skeyword) {
                    if (stristr($cvText, $skeyword) !== false) {
                        if ( in_array($question->type, ['Select', 'Check']) ) {
                            $question->options = Option::where('qid', $question->id)->get();
                        }
                        $selectedQuestions[] = $question;
                        break 2;
                    }
                }
            }
        }
        
        return $selectedQuestions;
    }

    private function getQuestionOptions($jobId)
    {
        $options = [];
        $qgroups = Job::find($jobId);
        
        if (!empty($qgroups->qgroups)) {
            $qgroups = explode(',', $qgroups->qgroups);
            $optionsRes = Option::whereIn('qid', function ($query1) use ($qgroups) {
                $query1->select('id')
                ->from( with( (new Question)->getTable() ) )
                ->whereIn('gid', $qgroups);
            })->get();
            foreach ($optionsRes as $opt) {
                $options[] = $opt->label;
            }
        }
        return $options;
    }

}
