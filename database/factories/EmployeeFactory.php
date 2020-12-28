<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'badge_id'            => $this->faker->ean8,
            'last_name'           => $this->faker->lastName,
            'first_name'          => $this->faker->firstName,
            'middle_name'         => $this->faker->lastName,
        ];
    }
}
