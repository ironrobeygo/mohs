<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $unit = $this->faker->name;

        return [
            'name' => $unit,
            'slug' => Str::of($unit)->slug('-'),
            'content' => $this->faker->sentence,
            'module_id' => 1,
            'user_id' => 1,
            'updated_by' => 1
        ];
    }
}
