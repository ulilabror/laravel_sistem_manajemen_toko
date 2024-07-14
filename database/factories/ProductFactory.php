<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
            'product_type' => $this->faker->word,
            'product_sku' => $this->faker->unique()->numerify('SKU-#####'),
            'product_description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(100, 10000),
            'created_by' => User::factory(),
        ];
    }
}
