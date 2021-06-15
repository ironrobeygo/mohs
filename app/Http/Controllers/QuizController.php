<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Course $course, Module $module){
        //create the quiz
        return $module->addQuiz(request()->all());

        // $question = $quiz->addQuestion();
        //select the question type

        //if question type is multiple choice or select all options
        //if($question->type)
        // $question->addOptions(); 

        //add the question
        //add the options - if applicable
    }

    public function update(Course $course, Module $module, Quiz $quiz){

        $quiz->update(request()->all());
        return $quiz;

    }

    public function destroy(Course $course, Module $module, Quiz $quiz){
        $quiz->delete();
        return $quiz;
    }
}
