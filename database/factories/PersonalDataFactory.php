<?php

namespace Database\Factories;

use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalData::class;

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
                  ->whereNotIn('id', PersonalData::pluck('employee_id')->toArray())
                  ->inRandomOrder()
                  ->first()->id;
            },
            'mobile_number'    => $this->faker->tollFreePhoneNumber,
            'telephone_number' => $this->faker->tollFreePhoneNumber,

            'company_email'    => $this->faker->unique()->safeEmail,
            'personal_email'   => $this->faker->unique()->safeEmail,
            
            'pagibig'          => $this->faker->ean8,
            'sss'              => $this->faker->ean8,
            'philhealth'       => $this->faker->ean8,
            'tin'              => $this->faker->ean8,

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
