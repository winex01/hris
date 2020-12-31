<?php

namespace Database\Factories;

use App\Models\Mother;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mother::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'relation'         => 'mother',
            'last_name'        => $this->faker->lastName,
            'first_name'       => $this->faker->firstName,
            'middle_name'      => $this->faker->tollFreePhoneNumber,

            'mobile_number'    => $this->faker->tollFreePhoneNumber,
            'telephone_number' => $this->faker->tollFreePhoneNumber,

            'company_email'    => $this->faker->unique()->safeEmail,
            'personal_email'   => $this->faker->unique()->safeEmail,

            'address'          => $this->faker->address,
            'city'             => $this->faker->city,
            'country'          => $this->faker->country,
            'zip_code'         => $this->faker->ean8,
            
            'occupation'       => $this->faker->jobTitle,
            'company'          => $this->faker->company,
            'company_address'  => $this->faker->address,

            'birth_date'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'birth_place'      => $this->faker->address,
            
            'personable_id' => function () {
                return \App\Models\Employee::select('id')
                  ->whereNotIn('id', 
                    Mother::pluck('personable_id')->toArray()
                  )
                  ->inRandomOrder()
                  ->first()->id;
            },
            'personable_type'  => 'App\Models\Employee',
        ];
    }
}
