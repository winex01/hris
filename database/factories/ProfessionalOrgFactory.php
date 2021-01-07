<?php

namespace Database\Factories;

use App\Models\ProfessionalOrg;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalOrgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProfessionalOrg::class;

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
            'organization_name' => $this->faker->word,
            'position'          => $this->faker->jobTitle,
            'membership_date'   => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
