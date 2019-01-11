<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
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
            'email' => 'required|email|max:255|unique:users',
            'mobile_no'=> 'required|digits:10',
            'password' => 'required|min:5|confirmed',
        ]);
        
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'mobile_no' => $request['mobile_no'],
            'password' => bcrypt($request['password']),
        ]);
        
        return redirect()->intended('/user');
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
        $user = User::find($id);
        
        // Redirect to user list if updating user wasn't existed
        if (empty($user)) {
            return redirect()->intended('/user');
        }
        
        return view('users.create', [
            'user' => $user,
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
        $user = User::findOrFail($id);
        
        $constraints = [
            'name' => 'required|max:100',
            'mobile_no'=> 'required|digits:10',
            'password' => 'required|min:5|confirmed',
        ];
        
        $input = [
            'name' => $request['name'],
            'mobile_no' => $request['mobile_no'],
        ];
        
        if ($request['password'] != null && strlen($request['password']) > 0) {
            $constraints['password'] = 'required|min:5|confirmed';
            $input['password'] =  bcrypt($request['password']);
        }
        
        $this->validate($request, $constraints);
        User::where('id', $id)
            ->update($input);
        
        return redirect()->intended('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->intended('/user');
    }
}
