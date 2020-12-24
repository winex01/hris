<?php

namespace Database\Factories;

use App\Models\SupportingDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportingDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupportingDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'employee_id'  => function (){
                return \App\Models\Employee::factory()->create()->id;
            },
            'document' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(2),
            'date_created' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now'),
        ];
    }
}
