<?php

namespace Database\Factories;

use App\Models\TrainingsAndSeminar;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingsAndSeminarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TrainingsAndSeminar::class;

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
            'organizer'      => $this->faker->word(2),
            'training_title' => $this->faker->sentence(1),
            'date_start'     => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'date_end'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'venue'          => $this->faker->paragraph(1),
        ];
    }
}
