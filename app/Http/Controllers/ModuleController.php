<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Resources\ModuleResource;

class ModuleController extends Controller
{
    public function index(Course $course){
        return ModuleResource::collection($course->modules);
    }

    public function store(Course $course){

        $validated = request()->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'updated_by' => 'required'
        ]);

        //validation
        return $course->addModule($validated);
    }

    public function update(Course $course, Module $module){

        $validated = request()->validate([
            'name'          => 'required',
            'slug'          => 'required',
            'description'   => 'nullable',
            'updated_by'    => 'required',
            'status'        => 'required'
        ]);

        $module->update($validated);
        return new ModuleResource($module);
    }

    public function show(Course $course, Module $module){

        return new ModuleResource($module);

    }

    public function destroy(Course $course, Module $module){
        $module->delete();
        return $module;
    }

    public function clone(Course $course, Module $module){

        $clone = $module->replicate();
        $clone->status = Module::DRAFT;
        $clone->user_id = auth()->user()->id;
        $clone->updated_by = auth()->user()->id;
        $clone->save();

        return $clone;
    }
}
