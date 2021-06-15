<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $options = Option::factory()
                    ->count(4)
                    ->create()
                    ->pluck('id')
                    ->values();

        return [
            'question'      => $this->faker->sentence.'?',
            'type_id'       => 1,
            'quiz_id'       => 1,
            'user_id'       => 1,
            'updated_by'    => 1,
            'status'        => Question::DRAFT
        ];
    }
}
