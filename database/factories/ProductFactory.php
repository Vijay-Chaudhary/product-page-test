<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productName = $this->faker->name();
        return [
            'name' => $productName,
            'description' => $this->faker->text(100),
            'slug' => Str::slug($productName),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'active' => $this->faker->boolean()
        ];
    }
}
