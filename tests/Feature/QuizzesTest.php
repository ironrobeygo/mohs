<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizzesTest extends TestCase
{
    protected $user, $course, $module;

    protected function setUp(): void{
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->course   = Course::factory()->create(['user_id' => $this->user->id]);

        $this->module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

    }

    //create a quiz
    /** @test */
    public function can_create_a_quiz(){

        $this->withoutExceptionHandling();

        $response = $this->post($this->module->path().'/quizzes', $this->data());
        $quizzes = $this->module->quizzes;

        $this->assertCount(1, $quizzes);
    }
    
    //update a quiz
    /** @test */
    public function can_update_a_quiz(){
        $this->withoutExceptionHandling();

        $newUser = User::factory()->create();

        $quiz = Quiz::factory()->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id,
            'updated_by'=> $this->user->id
        ]);

        $this->patch(
            $quiz->path(), 
            array_merge(
                $this->data(), 
                [
                    'name'          => 'This is the edited quiz',
                    'slug'          => 'this-is-the-edited-quiz',
                    'updated_by'    => $newUser->id,
                    'status'        => Quiz::INACTIVE
                ]
            )
        );

        $quiz = $this->module->quizzes()->first();

        $this->assertEquals('This is the edited quiz', $quiz->name);
        $this->assertEquals('this-is-the-edited-quiz', $quiz->slug);
        $this->assertEquals($newUser->id, $quiz->updated_by);
        $this->assertEquals(Quiz::INACTIVE, $quiz->status);

    }
    //delete a quiz
    //admin and instructor can edit a quiz

    //create a quiz
    /** @test */
    public function can_delete_a_quiz(){

        $this->post($this->module->path().'/quizzes', $this->data());
        $response = $this->delete($this->module->quizzes()->first()->path());
        $response->assertStatus(200);
        $this->assertCount(0, $this->module->quizzes);
    }



    protected function data(){

        $quiz = 'Quiz Title 1';

        return [
            'name'          => $quiz,
            'slug'          => Str::of($quiz)->slug('-'),
            'module_id'     => $this->module->id,
            'user_id'       => $this->user->id,
            'updated_by'    => $this->user->id,
            'status'        => Quiz::DRAFT
        ];

    }

}
