<?php

namespace Database\Factories;

use App\Models\GovernmentExamination;
use Illuminate\Database\Eloquent\Factories\Factory;

class GovernmentExaminationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GovernmentExamination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'employee_id' => function (){
                return \App\Models\Employee::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'title'       => $this->faker->sentence(1),
            'institution' => $this->faker->sentence(2),
            'date'        => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'venue'       => $this->faker->paragraph,
            'rating'      => rand(70,100),
        ];
    }
}
