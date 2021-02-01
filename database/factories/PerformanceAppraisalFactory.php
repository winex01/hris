<?php

namespace Database\Factories;

use App\Models\PerformanceAppraisal;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceAppraisalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PerformanceAppraisal::class;

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
            'date_evaluated'   => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'appraisal_type_id' => function (){
                return \App\Models\AppraisalType::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'appraiser_id' => function (){
                return \App\Models\Employee::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'job_function'            => $this->faker->numberBetween(7, 10),
            'productivity'            => $this->faker->numberBetween(7, 10),
            'attendance'              => $this->faker->numberBetween(7, 10),
            'planning_and_organizing' => $this->faker->numberBetween(7, 10),
            'innovation'              => $this->faker->numberBetween(7, 10),
            'technical_domain'        => $this->faker->numberBetween(7, 10),
            'sense_of_ownership'      => $this->faker->numberBetween(7, 10),
            'customer_relation'       => $this->faker->numberBetween(7, 10),
            'professional_conduct'    => $this->faker->numberBetween(7, 10),
        ];
    }
}
