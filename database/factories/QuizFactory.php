<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $quiz = 'Quiz Title 1';

        return [
            'name' => $quiz,
            'slug' => Str::of($quiz)->slug('-'),
            'module_id' => 1,
            'user_id' => 1,
            'updated_by' => 1
        ];
    }
}
