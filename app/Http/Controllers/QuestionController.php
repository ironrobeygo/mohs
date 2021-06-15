<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Course $course, Module $module, Quiz $quiz){

        $question = $quiz->addQuestion(request()->all());
        $question->syncOptions(request()->options);
        $question->syncAnswer(request()->answers);

        return $question;
    }

    public function update(Course $course, Module $module, Quiz $quiz, Question $question){
        $question->update(request()->all());
        $question->syncOptions(request()->options);
        $question->syncAnswer(request()->answers);

        return $question;
    }
}
