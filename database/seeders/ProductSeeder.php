<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $products = [
                [
                    'product_name' => 'Product 1',
                    'product_type' => 'Type 1',
                    'product_sku' => Str::random(10),
                    'product_description' => 'Description for Product 1',
                    'price' => 100,
                    'created_by' => $user->id,
                ],
                [
                    'product_name' => 'Product 2',
                    'product_type' => 'Type 2',
                    'product_sku' => Str::random(10),
                    'product_description' => 'Description for Product 2',
                    'price' => 200,
                    'created_by' => $user->id,
                ],
                // Add more products as needed
            ];

            foreach ($products as $productData) {
                $product = Product::create($productData);

                // Create fake images
                Storage::fake('public');

                $files = [
                    UploadedFile::fake()->image('product_image_1.jpg'),
                    UploadedFile::fake()->image('product_image_2.png'),
                    // Add more files as needed
                ];

                foreach ($files as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('public/files', $filename);
                    $url = url(Storage::url($path));
                    $product->files()->create([
                        'filename' => $filename,
                        'path' => $path,
                        'url' => $url,
                        'uploaded_by' => $user->id,
                        'related_id' => $product->id,
                        'related_type' => Product::class,
                    ]);
                }
            }
        }
    }
}
