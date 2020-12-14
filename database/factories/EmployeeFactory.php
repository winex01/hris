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
            'badge_id' => $this->faker->ean8,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            // 'address' => $this->faker->address,
            // 'city' => $this->faker->city,
            // 'country' => $this->faker->country,
            // 'zip_code' => $this->faker->ean8,
            // 'birth_date' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            // 'birth_place' => $this->faker->address,
            // 'mobile_number' => $this->faker->tollFreePhoneNumber,
            // 'telephone_number' => $this->faker->tollFreePhoneNumber,
            // 'company_email' => $this->faker->unique()->safeEmail,
            // 'personal_email' => $this->faker->unique()->safeEmail,
            // 'pagibig' => $this->faker->ean8,
            // 'sss' => $this->faker->ean8,
            // 'philhealth' => $this->faker->ean8,
            // 'tin' => $this->faker->ean8,
            // 'date_applied' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            // 'date_hired' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
