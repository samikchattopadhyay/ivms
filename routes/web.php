<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);


Route::group(['middleware' => 'auth'], function () {
    
    Route::resource('user', 'UserController');
    Route::resource('job', 'JobsController');
    Route::resource('question', 'QuestionsController');
    Route::resource('candidate', 'CandidatesController');

    Route::post('/users/notified', 'UserController@notified');
    
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::get('/questions/group', 'QuestionsController@group')->name('qgroup');
    Route::post('/questions/group/store', 'QuestionsController@storeGroup')->name('qgroup.store');
    Route::patch('/questions/group/update', 'QuestionsController@updateGroup')->name('qgroup.update');
    Route::delete('/questions/group/descroy', 'QuestionsController@destroyGroup')->name('qgroup.destroy');
    
    Route::get('/options/destroy','QuestionsController@destroyOption');
    
    Route::get('/candidates/recalc', 'CandidatesController@recalc')->name('candidates.recalculate');
    Route::get('/candidates/answer', 'CandidatesController@answer')->name('candidates.answer');
    Route::get('/candidates/comments/{cid}', 'CandidatesController@comments')->name('candidates.comments');
    Route::post('/candidates/comment', 'CandidatesController@comment')->name('candidates.comment');
    Route::post('/candidates/status', 'CandidatesController@status')->name('candidates.status');
    Route::post('/candidates/interview', 'CandidatesController@interview')->name('candidates.interview');
    Route::get('/candidates/preview/{cid}', 'CandidatesController@preview')->name('candidates.preview');
    Route::get('/candidates/preview/{cid}/{ex}', 'CandidatesController@preview')->name('candidates.expreview');
    
    Route::get('/candidates/download-cv/{cid}', 'CandidatesController@load')->name('candidates.resume');
    Route::get('/candidates/email-qset/{cid}', 'CandidatesController@emailQset')->name('candidates.email');
    Route::get('/candidates/test-email', 'CandidatesController@testEmail');
    
});

Route::get('/qset/{session}', 'CandidatesController@qset')->name('candidates.qset');
Route::post('/qset/answer', 'CandidatesController@qsetAnswer')->name('candidates.answer');


