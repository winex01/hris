<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'badge_id'            => $this->faker->ein, 
            'last_name'           => $this->faker->lastName,
            'first_name'          => $this->faker->firstName,
            'middle_name'         => $this->faker->lastName,

            'mobile_number'    => $this->faker->mobileNumber,
            'telephone_number' => $this->faker->tollFreePhoneNumber,
            'company_email'    => $this->faker->unique()->safeEmail,
            'personal_email'   => $this->faker->unique()->safeEmail,

            'pagibig'          => $this->faker->isbn10,
            'sss'              => $this->faker->bankAccountNumber,
            'philhealth'       => $this->faker->bankRoutingNumber,
            'tin'              => $this->faker->taxpayerIdentificationNumber,

            'address'          => $this->faker->address,
            'city'             => $this->faker->city,
            'country'          => $this->faker->country,
            'zip_code'         => $this->faker->ean8,

            'gender_id'        => function (){
                return \App\Models\Gender::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'civil_status_id'  => function (){
                return \App\Models\CivilStatus::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'citizenship_id'  => function (){
                return \App\Models\Citizenship::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'religion_id'   => function (){
                return \App\Models\Religion::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },
            'blood_type_id'   => function (){
                return \App\Models\BloodType::select('id')
                  ->inRandomOrder()
                  ->first()->id;
            },

            'birth_date'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'birth_place'      => $this->faker->address,

            'date_applied'     => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
            'date_hired'       => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
