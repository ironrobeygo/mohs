<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Option;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OptionsTest extends TestCase
{
    protected $user;

    protected function setUp(): void{
        parent::setUp();
        $this->user     = User::factory()->create();
    }

    /** @test */
    public function can_add_an_option(){

        $this->withoutExceptionHandling();

        $response = $this->post('/api/options', $this->data());
        $options  = Option::all();

        $this->assertCount(1, $options);
    }

    /** @test */
    public function can_update_an_option(){

        $newUser = User::factory()->create();

        $option = Option::factory()->create();

        $this->patch($option->path(), 
            array_merge(
                $this->data(),
                [
                    'option'    => 'This is an edited option',
                    'updated_by' => $newUser->id
                ]
            )
        );

        $options = Option::all();

        $this->assertCount(1, $options);
        $this->assertEquals('This is an edited option', $options[0]->option);

    }

    /** @test */
    public function can_delete_an_option(){

        $option = Option::factory()->create($this->data());

        $response = $this->delete($option->path());
        $response->assertStatus(200);
        $this->assertCount(0, Option::all());

    }



    protected function data(){

        return [
            'option'        => 'This is an option',
            'user_id'       => $this->user->id,
            'updated_by'    => $this->user->id,
        ];

    }
}
