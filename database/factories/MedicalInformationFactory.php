<?php

namespace Database\Factories;

use App\Models\MedicalInformation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalInformationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalInformation::class;

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
            'medical_examination_or_history' => $this->faker->sentence(5),
            'date_taken'                     => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'expiration_date'                => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'diagnosis'                      => $this->faker->paragraph(2),
        ];
    }
}
