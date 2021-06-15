<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void{
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_get_all_courses(){
        $courses = Course::factory()->count(10)->create();

        $response = $this->get('/api/courses');

        $response->assertJsonCount(10);
    }

    /** @test */
    public function can_create_a_course(){
        $this->signIn($this->user);
        $courses = $this->data();

        $this->post('/api/courses', $courses);
        $course = Course::first();

        $this->assertDatabaseHas('courses', $courses);
    }
    //only admin can add a course

    /** @test */
    public function can_update_a_course(){

        $course = Course::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'name'          => $course->name,
            'slug'          => $course->slug,
            'description'   => 'This is the new course description',
            'category_id'   => $course->category_id,
            'instructor_id' => 2,
            'updated_by'    => 1,
            'status'        => Course::PENDING
        ];

        $this->actingAs($course->user)
            ->patch($course->path(), $data);

        $course = $course->fresh();

        $this->assertEquals('This is the new course description', $course->description);
        $this->assertEquals(Course::PENDING, $course->status);

    }
    //only admin can update a course

    /** @test */
    public function can_delete_a_course(){

        $course = Course::factory()->create();
        $response = $this->delete($course->path());
        $response->assertStatus(200);
        $this->assertCount(0, Course::all());

    }
    //only admin can delete a course

    /** @test */
    public function can_only_be_viewed_when_online(){
        $course = Course::factory()->create(['status' => Course::ONLINE]);
        $response = $this->get($course->path());
        $this->assertInstanceOf(Course::class, $response->original);
    }
    //course is only viewable when online
    //course is viewable only when logged in 
    //students can view the course
    //instructors and admin can preview the course in pending
    //instructors and admin can view the list of modules

    /** @test */
    public function check_if_validation_throws_error_on_course_create(){
        
        $this->signIn($this->user);

        collect([
            'name',
            'slug',
            'category_id',
            'instructor_id',
            'updated_by'
        ])
        ->each( function($field){

            $response = $this->post('/api/courses', 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
        });

        $this->assertCount(0, Course::all());
    }

    /** @test */
    public function check_if_validation_throws_error_on_course_update(){

        // $this->signIn($this->user);
        $course = Course::factory()->create($this->data());

        collect([
            'name',
            'slug',
            'category_id',
            'instructor_id',
            'updated_by',
            'status'
        ])
        ->each( function($field) use($course){

            $response = $this->patch($course->path(), 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
        });

        $course = Course::first();

        $this->assertCount(1, Course::all());
        $this->assertEquals('Course Title 1', $course->name);
        $this->assertEquals('course-title-1', $course->slug);
        $this->assertEquals('this is the description', $course->description);
        $this->assertEquals(1, $course->category_id);
        $this->assertEquals(Course::DRAFT, $course->status);
    }

    protected function data(){

        $course = 'Course Title 1';

        return [
            'name'          => $course,
            'slug'          => Str::of($course)->slug('-'),
            'description'   => 'this is the description',
            'category_id'   => 1,
            'instructor_id' => 2,
            'user_id'       => 1,
            'updated_by'    => 1
        ];

    }

}