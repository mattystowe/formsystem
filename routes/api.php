<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::get('/','Api\IndexController@index');



//Form - Get a form definition by its uid
Route::get('/forms/get/{uid}','Api\FormController@getForm');
//Forms - Get a results list of forms by ?tag and/or ?name search.
Route::get('/forms/listbytag/{formtag}','Api\FormController@listByTag');
//Forms - Add a new form with its definition to the system
Route::post('/forms/add','Api\FormController@addForm');
//Forms - Update an existing form definition
Route::post('/forms/update','Api\FormController@updateForm');
//Forms - search
Route::post('/forms/search','Api\FormController@search');




//Submissions - Get a list of submissions for a specific form
Route::get('/submissions/listbyform/{formuid}','Api\SubmissionController@listByForm');
//Submissions - Get a list of all form submissions available with a certain tag
Route::get('/submissions/listbytag/{submissiontag}','Api\SubmissionController@listByTag');
//Submissions - Get a list of submissions for a specific form with specific tag
Route::get('/submissions/listbytagandform/{submissiontag}/{formuid}','Api\SubmissionController@listByTagAndForm');
//Subbmissions - Get a submission by its uid
Route::get('/submissions/get/{submissionuid}','Api\SubmissionController@getByUid');
//Submissions - Add a submission
Route::post('/submissions/add','Api\SubmissionController@add');
//Submissions - Search
Route::post('/submissions/search','Api\SubmissionController@search');


//Form Item Types - Get a list of available item Types
Route::get('/formitemtypes/list','Api\FormItemTypesController@getAll');
