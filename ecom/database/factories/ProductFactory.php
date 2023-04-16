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
        $name = $this->faker->word();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '_' . $this->faker->numberBetween(),
            'description' => $this->faker->realText(100),
            'price' => $this->faker->numberBetween(500, 50000)
        ];
    }
}
