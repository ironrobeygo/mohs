<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Resources\UnitResource;

class UnitController extends Controller
{

    public function index(Course $course, Module $module){

        return UnitResource::collection($module->units);

    }
    public function store(Course $course, Module $module){

        $validated = request()->validate([
            'name'          => 'required',
            'slug'          => 'required',
            'content'       => 'required',
            'user_id'       => 'required',
            'updated_by'    => 'required'
        ]);

        return $module->addUnit($validated);
    }

    public function show(Course $course, Module $module, Unit $unit){

        return new UnitResource($unit);

    }

    public function update(Course $course, Module $module, Unit $unit){

        $validated = request()->validate([
            'name'          => 'required',
            'slug'          => 'required',
            'content'       => 'required',
            'updated_by'    => 'required',
            'status'        => 'required'
        ]);

        $unit->update($validated);
        return new UnitResource($unit);
    }

    public function destroy(Course $course, Module $module, Unit $unit){
        $unit->delete();
        return $unit;
    }

    public function clone(Course $course, Module $module, Unit $unit){
        $clone = $unit->replicate();
        $clone->status = Unit::DRAFT;
        $clone->user_id = auth()->user()->id;
        $clone->updated_by = auth()->user()->id;
        $clone->save();

        return $clone;
    }
}