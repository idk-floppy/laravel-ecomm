<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randoms = ['Shirt', 'Mug', 'Broccoli', 'Whatever', 'WTF', 'SWAG', 'DontBuyMe', 'Karen'];
        $name = $this->faker->firstName()
            . " " . $this->faker->randomElement($randoms)
            . " " . $this->faker->word()
            . " " . $this->faker->safeColorName();
        return [
            'name' => $name,
            'price' => $this->faker->numberBetween(500, 50000)
        ];
    }
}
