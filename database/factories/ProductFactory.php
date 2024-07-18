<?php

namespace Database\Factories;

use App\Models\Product;
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
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'discount' => $this->faker->optional()->randomFloat(2, 0, 100),
            'thumbnail' => $this->faker->imageUrl(640, 480, 'products', true),
            'images' => json_encode([
                $this->faker->imageUrl(640, 480, 'products', true),
                $this->faker->imageUrl(640, 480, 'products', true),
                $this->faker->imageUrl(640, 480, 'products', true),
            ]),
            'status' => $this->faker->randomElement(['publish', 'unpublish']),
        ];
    }
}
