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
    
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::get('/questions/group', 'QuestionsController@group')->name('qgroup');
    Route::post('/questions/group/store', 'QuestionsController@storeGroup')->name('qgroup.store');
    Route::patch('/questions/group/update', 'QuestionsController@updateGroup')->name('qgroup.update');
    Route::delete('/questions/group/descroy', 'QuestionsController@destroyGroup')->name('qgroup.destroy');
    
    Route::get('/options/destroy','QuestionsController@destroyOption');
    
    Route::get('/candidates/recalc', 'CandidatesController@recalc')->name('candidates.recalculate');
    Route::get('/candidates/qset', 'CandidatesController@qset')->name('candidates.qset');
    Route::get('/candidates/answer', 'CandidatesController@answer')->name('candidates.answer');
    Route::get('/candidates/comments/{cid}', 'CandidatesController@comments')->name('candidates.comments');
    Route::post('/candidates/comment', 'CandidatesController@comment')->name('candidates.comment');
    Route::get('/candidates/preview/{cid}', 'CandidatesController@preview')->name('candidates.preview');
    Route::get('/candidates/resume/{cid}', 'CandidatesController@load')->name('candidates.resume');
    
    Route::get('/candidates/test-email', 'CandidatesController@testEmail');
    
});


