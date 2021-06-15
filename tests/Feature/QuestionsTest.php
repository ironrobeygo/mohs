<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Option;
use App\Models\Question;
use Database\Seeders\OptionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionsTest extends TestCase
{
    protected $user, $course, $module, $quiz;

    protected function setUp(): void{

        parent::setUp();
        $this->user     = User::factory()->create();
        $this->course   = Course::factory()->create(['user_id' => $this->user->id]);

        $this->module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

        $this->quiz = Quiz::factory()->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id,
            'updated_by'=> $this->user->id
        ]);

    }

    //create a quiz
    /** @test */
    public function can_create_a_multiple_choice_question_with_single_answer(){

        $options = Option::factory()->count(4)->create();

        $optionItems = $options->pluck('id')->toArray();
        $answer = $options->take(1)->pluck('id')->toArray();

        $response = $this->post(
            $this->quiz->path().'/questions', 
            array_merge(
                $this->data(),
                array(
                    'options' => $optionItems,
                    'answers'  => $answer
                )
            )
            
        );
        $questions = $this->quiz->questions;

        $this->assertCount(1, $questions);
        $this->assertCount(4, $questions[0]->options);
        $this->assertEquals($answer[0], $questions[0]->answers()->first()->id);
    }

    /** @test */
    public function can_update_a_multiple_choice_question_with_single_answer(){

        $newUser = User::factory()->create();

        $options = Option::factory()->count(4)->create();

        $optionItems = $options->pluck('id')->toArray();
        $answer = $options->first()->pluck('id')->toArray();

        $this->post(
            $this->quiz->path().'/questions', 
            array_merge(
                $this->data(),
                array(
                    'options' => $optionItems,
                    'answers'  => $answer
                )
            )
            
        );

        $question = Question::first();

        $updatedAnswer = $options->get(2)->id;

        $this->patch($question->path(), 
            array_merge(
                $this->data(),
                [
                    'updated_by'    => $newUser->id,
                    'status'        => Question::PENDING,
                    'options'       => $optionItems,
                    'answers'       => array($updatedAnswer)
                ]
            )
        );

        $question = $this->quiz->questions()->first();

        $this->assertCount(1, $this->quiz->questions);
        $this->assertEquals($newUser->id, $question->updated_by);
        $this->assertEquals($updatedAnswer, $question->answers()->first()->id);
        $this->assertEquals(Question::PENDING, $question->status);
        $this->assertCount(4, $question->options);

    }

    /** @test */
    public function can_create_a_multiple_choice_question_with_multiple_answer(){

        $options = Option::factory()->count(4)->create();

        $optionItems = $options->pluck('id')->toArray();
        $answers = $options->pluck('id')->take(3)->toArray();

        $response = $this->post(
            $this->quiz->path().'/questions', 
            array_merge(
                $this->data(),
                array(
                    'status'    => Question::PENDING,
                    'options'   => $optionItems,
                    'answers'   => $answers
                )
            )
            
        );

        $questions = $this->quiz->questions;
        $question = $this->quiz->questions()->first();

        $this->assertCount(1, $questions);
        $this->assertCount(4, $question->options);
        $this->assertEquals(Question::PENDING, $question->status);
        $this->assertCount(3, $question->answers);
    }

    /** @test */
    public function can_update_a_multiple_choice_question_with_multiple_answer(){
        // $this->withoutExceptionHandling();

        $newUser = User::factory()->create();

        $options = Option::factory()->count(4)->create();

        $optionItems = $options->pluck('id')->toArray();
        $answers = $options->pluck('id')->take(3)->toArray();

        $this->post(
            $this->quiz->path().'/questions', 
            array_merge(
                $this->data(),
                array(
                    'options' => $optionItems,
                    'answers'  => $answers
                )
            )
            
        );

        $question = Question::first();

        $updatedAnswers = $options->pluck('id')->take(2)->toArray();

        $this->patch($question->path(), 
            array_merge(
                $this->data(),
                [
                    'updated_by'    => $newUser->id,
                    'status'        => Question::PENDING,
                    'options'       => $optionItems,
                    'answers'       => $updatedAnswers
                ]
            )
        );

        $questions = $this->quiz->questions;
        $question = $this->quiz->questions()->first();

        $this->assertCount(1, $questions);
        $this->assertCount(4, $question->options);
        $this->assertCount(2, $question->answers);
    }

    /** @test */
    public function can_create_an_open_ended_question(){

        $this->post(
            $this->quiz->path().'/questions', 
            $this->data([
                'type_id'   => 3
            ])
        );

        $question = $this->quiz->questions()->first();

        $this->assertCount(1, $this->quiz->questions);
        $this->assertCount(0, $question->options);
        $this->assertCount(0, $question->answers);

    }

    /** @test */
    public function can_update_an_open_ended_question(){

        $this->post(
            $this->quiz->path().'/questions', 
            $this->data([
                'type_id'   => 3
            ]) 
        );

        $question = $this->quiz->questions()->first();

        $this->assertCount(1, Question::all());
        $this->assertCount(0, $question->options);
        $this->assertCount(0, $question->answers);

    }

    /** @test */
    public function can_create_a_true_or_false_question(){
        
        $this->seed(OptionSeeder::class);
        //get true or false options

        $options = Option::truefalseOptions()->pluck('id')->toArray();

        $this->post(
            $this->quiz->path().'/questions', 
            array_merge(
                $this->data(),
                array(
                    'type_id'   => 2,
                    'options'   => $options,
                    'answers'   => array($options[0])
                )
            )
        );

        $question = $this->quiz->questions()->first();

        $this->assertCount(1, $this->quiz->questions);
        $this->assertCount(2, $question->options);
        $this->assertCount(1, $question->answers);

    }

    protected function data(){

        return [
            'question'      => 'What is your question?',
            'type_id'       => 1,
            'quiz_id'       => $this->quiz->id,
            'user_id'       => $this->user->id,
            'updated_by'    => $this->user->id,
            'status'        => Question::DRAFT
        ];

    }
}
