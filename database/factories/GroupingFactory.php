<?php

namespace Database\Factories;

use App\Models\Grouping;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grouping::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => strtoupper($this->faker->word),
        ];
    }
}
