<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        return [
            'filename' => $this->faker->word . '.' . $this->faker->fileExtension,
            'path' => 'public/files/' . $this->faker->word . '.' . $this->faker->fileExtension,
            'uploaded_by' => User::factory(),
            'related_id' => Product::factory(),
            'related_type' => Product::factory(),
        ];
    }
}
