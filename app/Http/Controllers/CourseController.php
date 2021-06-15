<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(){
        return Course::all();
    }

    public function store(){

        $validated = request()->validate([
            'name'          => 'required',
            'slug'          => 'required',
            'description'   => 'nullable',
            'category_id'   => 'required',
            'instructor_id' => 'required',
            'user_id'       => 'required',
            'updated_by'    => 'required'
        ]);

        return Course::create($validated);

        // return auth()->user()->addCourse($validated);      
    }

    public function show(Course $course){

        if( !$course->isOnline() ) return false;

        return $course;

    }

    public function update(Course $course){

        $validated = request()->validate([
            'name'          => 'required',
            'slug'          => 'required',
            'description'   => 'nullable',
            'category_id'   => 'required',
            'instructor_id' => 'required',
            'updated_by'    => 'required',
            'status'        => 'required'
        ]);

        $course->update($validated);

        return $course;

    }

    public function destroy(Course $course){

        $course->delete();

        return $course;

    }
}
