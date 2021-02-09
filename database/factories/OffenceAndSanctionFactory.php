<?php

namespace Database\Factories;

use App\Models\OffenceAndSanction;
use Illuminate\Database\Eloquent\Factories\Factory;

class OffenceAndSanctionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OffenceAndSanction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => function (){
                return \App\Models\Employee::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'date_issued' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'offence_classification_id' => function (){
                return \App\Models\OffenceClassification::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'gravity_of_sanction_id' => function (){
                return \App\Models\OffenceClassification::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'description' => $this->faker->sentence(6),
        ];
    }
}
