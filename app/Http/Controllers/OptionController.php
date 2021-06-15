<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function store(){

        return Option::create(request()->all());

    }

    public function update(Option $option){

        $option->update(request()->all());
        return $option;

    }

    public function destroy(Option $option){

        $option->delete();

    }
}
