<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Str;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Role::factory()->create();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function it_can_list_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_show_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'product_name' => $product->product_name
            ]);
    }

    /** @test */
    public function it_can_create_a_product_with_multiple_files()
    {
        Storage::fake('public');

        $user = $this->user;

        $files = [
            UploadedFile::fake()->image('example1.jpg'),
            UploadedFile::fake()->image('example2.jpg'),
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'files' => $files,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product created successfully',
            ]);

        // Check if the product was created in the database
        $this->assertDatabaseHas('products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'created_by' => $user->id,
        ]);
    }
    /** @test */
    public function it_can_create_a_product_with_file()
    {
        Storage::fake('public');


        $file = [UploadedFile::fake()->image('example.jpg')];

        $response = $this->postJson('/api/products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'files' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product created successfully',
            ]);

        // Check if the file was stored
        // Storage::disk('public')->assertExists('files/' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension());

        // Check if the product was created in the database
        $this->assertDatabaseHas('products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'created_by' => $this->user->id,
        ]);

        // Check if the file record was created in the database
        $this->assertDatabaseHas('files', [
            // 'filename' => time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension(),
            'uploaded_by' => $this->user->id,
            // 'related_id' => Product::first()->id,
            'related_type' => Product::class,
        ]);

        // $filePath = str_replace('public/', '', $response->json('file.path'));
        // $this->assertFileExists(storage_path('app/public/files/' . 'files/' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension()));
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create();

        $data = [
            'product_name' => 'Updated Product',
            'product_type' => 'Updated Type',
            'product_sku' => 'UPDATEDSKU',
            'product_description' => 'Updated Description',
            'price' => 5678
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['product_name' => 'Updated Product']);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
