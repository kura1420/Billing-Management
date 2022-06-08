<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'active' => $this->faker->boolean(),
            'ppn_tax_id' => \App\Models\Tax::inRandomOrder()->first()->id,
        ];
    }
}
