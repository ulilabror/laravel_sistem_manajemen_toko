<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        // Generating a unique file name
        $filename = Str::uuid() . '.' . $this->faker->fileExtension;

        // Generating a storage path
        $path = 'public/files/' . $filename;

        // Generating a full URL
        $fullUrl = url(Storage::url($path));

        return [
            'filename' => $filename,
            'path' => $path,
            'url' => $fullUrl,
            'uploaded_by' => User::factory(),
            'related_id' => Product::factory(),
            'related_type' => Product::class,
        ];
    }
}
