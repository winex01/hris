<?php

namespace Database\Factories;

use App\Models\FamilyData;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FamilyData::class;

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

            'relation_id' => function (){
                return \App\Models\Relation::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },

            'last_name'        => $this->faker->lastName,
            'first_name'       => $this->faker->firstName,
            'middle_name'      => $this->faker->lastName,

            'mobile_number'    => $this->faker->tollFreePhoneNumber,
            'telephone_number' => $this->faker->tollFreePhoneNumber,

            'company_email'    => $this->faker->unique()->safeEmail,
            'personal_email'   => $this->faker->unique()->safeEmail,

            'address'          => $this->faker->address,
            'city'             => $this->faker->city,
            'country'          => $this->faker->country,
            
            'occupation'       => $this->faker->jobTitle,
            'company'          => $this->faker->company,
            'company_address'  => $this->faker->address,

            'birth_date'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
