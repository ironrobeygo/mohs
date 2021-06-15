<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $categories = Category::factory()->create();

        $name = $this->faker->name();

        return [
            'name'          => $name,
            'slug'          => Str::of($name)->slug('-'),
            'description'   => null,
            'category_id'   => $categories->first()->id,
            'instructor_id' => 2,
            'user_id'       => 1,
            'updated_by'    => 1,
            'status'        => Course::DRAFT
        ];
    }
}
