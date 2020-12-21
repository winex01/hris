<?php

namespace Database\Factories;

use App\Models\AwardAndRecognition;
use Illuminate\Database\Eloquent\Factories\Factory;

class AwardAndRecognitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AwardAndRecognition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            'employee_id'  => function (){
                $employee = \App\Models\Employee::factory()->create()->first();
                return $employee->id;
            },
            'company_name' => $this->faker->company,
            'award'        => $this->faker->title,
            'date_given'   => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
