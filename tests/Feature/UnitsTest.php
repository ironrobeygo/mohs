<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Unit;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnitsTest extends TestCase
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

    /** @test */
    public function view_all_course_module_units(){

        $units = Unit::factory()->count(10)->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id
        ]);

        $response = $this->get($this->module->path().'/units');
        $response->assertJsonCount(10);

        $response->assertStatus(200);

    }

    /** @test */
    public function can_create_a_unit()
    {
        $response = $this->post($this->module->path().'/units', $this->data());
        $units = $this->module->units;

        $this->assertCount(1, $units);
    }
    //admin can add units

    /** @test */
    public function can_update_a_unit(){

        $newUser = User::factory()->create();

        $unit = Unit::factory()->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id,
            'updated_by'=> $this->user->id
        ]);

        $this->patch(
            $unit->path(), 
            array_merge(
                $this->data(), 
                [
                    'name'          => 'This is the edited unit',
                    'slug'          => 'this-is-the-edited-unit',
                    'content'       => 'This is the updated course content',
                    'updated_by'    => $newUser->id,
                    'status'        => Unit::INACTIVE
                ]
            )
        );

        $unit = $this->module->units()->first();

        $this->assertEquals('This is the edited unit', $unit->name);
        $this->assertEquals('this-is-the-edited-unit', $unit->slug);
        $this->assertEquals('This is the updated course content', $unit->content);
        $this->assertEquals($newUser->id, $unit->updated_by);
        $this->assertEquals(Unit::INACTIVE, $unit->status);
    }
    //admin can edit units

    //admin can delete units
    /** @test */
    public function can_delete_a_unit(){
        $unit = Unit::factory()->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id,
            'updated_by'=> $this->user->id
        ]);

        $response = $this->delete($unit->path());
        $response->assertStatus(200);
        $this->assertCount(0, Unit::all());
    }

    //only admin can clone modules
    /** @test */
    public function can_clone_modules(){

        $newUser = User::factory()->create();
        $unit = Unit::factory()->create([
            'module_id' => $this->module->id,
            'user_id'   => $this->user->id,
            'updated_by'=> $this->user->id
        ]);

        $response = $this->actingAs($newUser)
                    ->post($unit->path().'/clone');
        $units = $this->module->units;

        $this->assertCount(2, $units);
        $this->assertEquals($newUser->id, $units[1]->user_id);
        $this->assertEquals($newUser->id, $units[1]->updated_by);
        $this->assertEquals(Module::DRAFT, $units[1]->status);

        $response->assertStatus(201);

    }

    /** @test */
    public function check_if_validation_throws_error_on_unit_create(){

        $this->signIn($this->user);

        collect([
            'name',
            'slug',
            'content',
            'user_id',
            'updated_by'
        ])
        ->each( function($field){

            $response = $this->post($this->module->path().'/units', 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Unit::all());
        });
    }

    /** @test */
    public function check_if_validation_throws_error_on_unit_update(){

        $this->signIn($this->user);

        Unit::factory()->create($this->data());

        $unit = $this->module->units()->first();

        collect([
            'name',
            'slug',
            'content',
            'updated_by',
            'status'
        ])
        ->each( function($field) use($unit){

            $response = $this->patch($unit->path(), 
                array_merge(
                    $this->data(), 
                    [$field => '']
                )
            );

            $response->assertSessionHasErrors($field);
        });

        $this->assertCount(1, Unit::all());
        $this->assertEquals('Unit Title 1', $unit->name);
        $this->assertEquals('unit-title-1', $unit->slug);
        $this->assertEquals('this is the unit content', $unit->content);
        $this->assertEquals(Unit::DRAFT, $unit->status);
    }

    protected function data(){

        $unit = 'Unit Title 1';

        return [
            'name'          => $unit,
            'slug'          => Str::of($unit)->slug('-'),
            'content'       => 'this is the unit content',
            'module_id'     => $this->module->id,
            'user_id'       => $this->user->id,
            'updated_by'    => $this->user->id
        ];

    }
}
