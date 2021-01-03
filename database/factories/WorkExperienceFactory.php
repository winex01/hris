<?php

namespace Database\Factories;

use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkExperience::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // TODO:: fix search error
        return [
            //
            'employee_id' => function (){
                return \App\Models\Employee::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'company'            => $this->faker->word(2),
            'position'           => $this->faker->sentence(1),
            'date_started'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'date_resign'        => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'salary'             => $this->faker->randomFloat(null, 15000, 100000),
            'reason_for_leaving' => $this->faker->paragraph(1),
        ];
    }
}
