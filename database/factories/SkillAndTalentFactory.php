<?php

namespace Database\Factories;

use App\Models\SkillAndTalent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillAndTalentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SkillAndTalent::class;

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
            'skill_or_talent'   => $this->faker->sentence(2),
            'description'       => $this->faker->sentence(6),
        ];
    }
}
