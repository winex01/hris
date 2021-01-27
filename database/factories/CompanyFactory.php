<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name'              => $this->faker->company,
            'address'           => $this->faker->address,
            'contact_person'    => $this->faker->firstName.' '.$this->faker->lastName,
            'fax_number'        => $this->faker->phoneNumber,
            'mobile_number'     => $this->faker->phoneNumber,
            'telephone_number'  => $this->faker->phoneNumber,
            'pagibig_number'    => $this->faker->ean8,
            'philhealth_number' => $this->faker->ean8,
            'tax_id_number'     => $this->faker->ean8,
            'bir_rdo'           => $this->faker->ean8,
        ];
    }
}
