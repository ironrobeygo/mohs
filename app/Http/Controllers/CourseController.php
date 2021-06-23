<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index(){

        $courses = Course::latest()->get();
        return CourseResource::collection($courses);
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

        $course = Course::create($validated);
        // $course = auth()->user()->addCourse($validated);    

        return new CourseResource($course);

  
    }

    public function show(Course $course){

        return new CourseResource($course);

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

        return new CourseResource($course);

    }

    public function destroy(Course $course){

        $course->delete();

        return new CourseResource($course);

    }
}
