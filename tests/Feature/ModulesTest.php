<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModulesTest extends TestCase
{

    protected $user, $course;

    protected function setUp(): void{
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->course   = Course::factory()->create(['user_id' => $this->user->id]);
    }

    //only admin can add modules
    /** @test */
    public function can_add_modules(){

        $response = $this->post($this->course->path().'/modules', $this->data());
        $module = $this->course->modules()->first();

        $this->assertDatabaseHas('modules', [
            'name' => 'Module Title 1',
            'slug' => 'module-title-1',
            'description' => 'this is the module description',
            'course_id' => $this->course->id,
            'user_id' => 1,
            'updated_by' => 1
        ]);

        $this->assertEquals('Module Title 1', $module->name);
        $this->assertEquals('module-title-1', $module->slug);
        $this->assertEquals('this is the module description', $module->description);
        $this->assertEquals($this->course->id, $module->course_id);

        $response->assertStatus(201);

    }

    /** @test */
    public function can_view_a_module(){
        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

        $response = $this->get($module->path());
        $response->assertOk();

    }

    /** @test */
    public function view_all_course_modules(){

        $modules = Module::factory()->count(10)->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

        $response = $this->get($this->course->path().'/modules');
        $response->assertJsonCount(10);

        $response->assertStatus(200);

    }

    //only admin can update modules
    /** @test */
    public function can_update_modules(){

        $newUser = User::factory()->create();
        
        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

        $data = [
            'name'          => 'This is the edited module',
            'slug'          => 'this-is-the-edited-module',
            'description'   => 'This is the updated course description',
            'updated_by'    => $newUser->id,
            'status'        => Module::INACTIVE
        ];

        $this->patch($module->path(), $data);

        $module = $this->course->modules()->first();

        $this->assertEquals('This is the edited module', $module->name);
        $this->assertEquals('this-is-the-edited-module', $module->slug);
        $this->assertEquals('This is the updated course description', $module->description);
        $this->assertEquals($newUser->id, $module->updated_by);
        $this->assertEquals(Module::INACTIVE, $module->status);

    }

    //only admin can delete modules
    /** @test */
    public function can_delete_modules(){

        $this->withoutExceptionHandling();

        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);
        $response = $this->delete($module->path());
        $response->assertStatus(200);
        $this->assertCount(0, Module::all());

    }

    //only admin can clone modules
    /** @test */
    public function can_clone_modules(){

        $this->withoutExceptionHandling();

        $newUser = User::factory()->create();

        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'user_id'   => $this->user->id
        ]);

        $response = $this->actingAs($newUser)
                    ->post($module->path().'/clone');
        $modules = $this->course->modules;

        $this->assertCount(2, $modules);
        $this->assertEquals($newUser->id, $modules[1]->user_id);
        $this->assertEquals($newUser->id, $modules[1]->updated_by);
        $this->assertEquals(Module::DRAFT, $modules[1]->status);

        $response->assertStatus(201);

    }
    //admin and instructors can view the modules

    /** @test */
    public function check_if_validation_throws_error_on_module_create(){

        $this->signIn($this->user);

        collect([
            'name',
            'slug',
            'description',
            'user_id',
            'updated_by'
        ])
        ->each( function($field){

            $response = $this->post($this->course->path().'/modules', 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Module::all());
        });
    }

    /** @test */
    public function check_if_validation_throws_error_on_module_update(){

        $name = 'Module Title 1';

        $module = Module::factory()->create([
            'name'          => $name,
            'slug'          => Str::of($name)->slug('-'),
            'description'   => 'this is the module description',
            'course_id'     => $this->course->id,
            'user_id'       => $this->user->id,
            'updated_by'    => $this->user->id
        ]);

        collect([
            'name',
            'slug',
            'updated_by',
            'status'
        ])
        ->each( function($field) use($module){

            $response = $this->patch($module->path(), 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
        });

        $module = Module::first();

        $this->assertCount(1, Module::all());
        $this->assertEquals('Module Title 1', $module->name);
        $this->assertEquals('module-title-1', $module->slug);
        $this->assertEquals('this is the module description', $module->description);
        $this->assertEquals(Module::DRAFT, $module->status);
    }

    protected function data(){

        $module = 'Module Title 1';

        return [
            'name'          => $module,
            'slug'          => Str::of($module)->slug('-'),
            'description'   => 'this is the module description',
            'user_id'       => 1,
            'updated_by'    => 1
        ];

    }

}
