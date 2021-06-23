<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void{
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_get_all_users(){
        User::factory()->count(9)->create();

        $response = $this->get('/api/users');

        $response->assertJsonCount(10);
    }
}
