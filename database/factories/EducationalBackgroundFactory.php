<?php

namespace Database\Factories;

use App\Models\EducationalBackground;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationalBackgroundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EducationalBackground::class;

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
            'educational_level_id' => function (){
                return \App\Models\EducationalLevel::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'course_or_major' => $this->faker->word,
            'school'          => $this->faker->company,
            'address'         => $this->faker->address,
            'date_from'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'date_to'         => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
