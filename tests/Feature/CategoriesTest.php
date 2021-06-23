<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    protected function setUp(): void{
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function can_get_all_categories(){

        $this->withoutExceptionHandling();

        $categories = Category::factory()->count(9)->create();

        $response = $this->get('/api/categories');

        $response->assertJsonCount(10);
    }
}
