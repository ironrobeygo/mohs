<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('courses','App\Http\Controllers\CourseController')->except(['create', 'edit']);
Route::resource('courses/{course}/modules','App\Http\Controllers\ModuleController')->except(['create', 'edit']);
Route::resource('courses/{course}/modules/{module}/units','App\Http\Controllers\UnitController')->except(['create', 'edit']);
Route::resource('courses/{course}/modules/{module}/quizzes','App\Http\Controllers\QuizController')->except(['create', 'edit']);
Route::resource('courses/{course}/modules/{module}/quizzes/{quiz}/questions','App\Http\Controllers\QuestionController')->except(['create', 'edit']);

Route::post('courses/{course}/modules/{module}/clone', 'App\Http\Controllers\ModuleController@clone')->name('modules.clone');
Route::post('courses/{course}/modules/{module}/units/{unit}/clone', 'App\Http\Controllers\UnitController@clone')->name('units.clone');

Route::resource('options','App\Http\Controllers\OptionController')->except(['create', 'edit']);