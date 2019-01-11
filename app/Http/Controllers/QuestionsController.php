<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestionGroup;
use App\Question;
use App\Option;

class QuestionsController extends Controller
{
    public function group(Request $request)
    {
        $group = [];
        $id = $request->id;
        $action = empty($request->id) ? 'Add New' : 'Edit';
        
        if (!empty($id)) {
            $group = QuestionGroup::where('id', $id)->first();
        }
        
        return view('questions.group', [
            'action' => $action,
            'group' => $group,
            'groups' => QuestionGroup::paginate(10)
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeGroup(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|max:100',
        ]);
        
        QuestionGroup::create([
            'group_name' => $request['group_name'],
        ]);
        
        return redirect()->intended(route('qgroup'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateGroup(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|max:100',
        ]);
        
        QuestionGroup::where('id', $request['id'])
            ->update([
                'group_name' => $request['group_name'],
            ]);
        
        return redirect()->intended(route('qgroup'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyGroup(Request $request)
    {
        $id = $request['id'];
        QuestionGroup::where('id', $id)->delete();
        return redirect()->intended(route('qgroup'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('questions.index', [
            'group' => QuestionGroup::where('id', $request['gid'])->first(),
            'questions' => Question::where('gid', $request['gid'])->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('questions.create', [
            'group' => QuestionGroup::where('id', $request['gid'])->first(),
            'questions' => Question::paginate(10),
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
            'question' => 'required',
            'keywords' => 'required',
            'type' => 'required',
            'gid' => 'required',
        ]);
        
        // Add the question
        $qId = Question::create([
            'question' => $request['question'],
            'keywords' => $request['keywords'],
            'type' => $request['type'],
            'gid' => $request['gid'],
        ])->id;
        
        $this->addEditOptions($qId, $request['options'], $request['newoptions']);
        
        return redirect()->intended( route('question.index', ['gid' => $request['gid']]) );
    }
    
    private function addEditOptions($qid, $options, $newOptions) {
        
        // Add the new options
        if (isset($newOptions) && count($newOptions)) {
            foreach ($newOptions as $label) {
                if (!empty($label)) {
                    Option::create([
                        'qid' => $qid,
                        'label' => $label,
                    ]);
                }
            }
            
        }
        
        // Edit existing options
        if (isset($options) && count($options)) {
            foreach ($options as $id => $label) {
                Option::where('id', $id)
                    ->update([
                        'label' => $label,
                    ]);
            }
        }
        
    }
    
    public function destroyOption(Request $request) {
        
        $response = array(
            'status' => 'success',
            'msg' => $request['id'],
        );
        
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        
        if (in_array($question->type, ['Select', 'Check'])) {
            $question->options = Option::where('qid', $question->id)->get();
        }
        
        return view('questions.create', [
            'group' => QuestionGroup::where('id', $question->gid)->first(),
            'question' => $question,
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
        $question = Question::findOrFail($id);
        
        $constraints = [
            'question' => 'required',
            'type' => 'required',
            'gid'=> 'required',
        ];
        
        $input = [
            'question' => $request['question'],
            'keywords' => $request['keywords'],
            'type' => $request['type'],
            'gid' => $request['gid'],
        ];
        
        $this->validate($request, $constraints);
        Question::where('id', $id)
            ->update($input);
        
        $this->addEditOptions($id, $request['options'], $request['newoptions']);
        
        return redirect()->intended( route('question.index', ['gid' => $question->gid ]) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        
        Question::where('id', $id)->delete();
        Option::where('qid', $id)->delete();
        
        return redirect()->intended( route('question.index', ['gid' => $question->gid ]) );
    }
}
