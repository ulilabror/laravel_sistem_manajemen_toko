<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

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
        Product::factory()->count(20)->create();
        $response = $this->getJson('/api/products?per_page=20');
        print_r($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [

                    'products' => [

                        '*' => [
                            'id',
                            'product_name',
                            'product_type',
                            'product_sku',
                            'product_label',
                            'product_description',
                            'product_barcode_id',
                            'price',
                            'created_by',
                            'created_at',
                            'updated_at',
                            'files' => [],
                        ],
                    ],
                    'pagination'


                ],
                'message',
                'errors',
            ])->assertJsonCount(20, 'data.products');
    }

    /** @test */
    public function it_can_show_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200)
            ->assertJsonFragment([
                'product_name' => $product->product_name
            ])
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'product_name',
                    'product_type',
                    'product_sku',
                    'product_label',
                    'product_description',
                    'product_barcode_id',
                    'price',
                    'created_at',
                    'updated_at',
                    'files' => [],
                ],
                'message',
                'errors',
            ]);
    }

    /** @test */
    public function it_can_create_a_product_with_files()
    {
        Storage::fake('public');

        $files = [
            UploadedFile::fake()->image('example1.jpg'),
            UploadedFile::fake()->image('example2.jpg'),
        ];

        $response = $this->postJson('/api/products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'files' => $files,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'product_name',
                    'product_type',
                    'product_sku',
                    'product_label',
                    'product_description',
                    'product_barcode_id',
                    'price',
                    'created_at',
                    'updated_at',
                    'files' => [
                        '*' => [
                            'id',
                            'filename',
                            'path',
                            'url',
                            'uploaded_by',
                            'related_id',
                            'related_type',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                'message',
                'errors',
            ]);

        // print_r($response->json());

        // Check if the product was created in the database
        $this->assertDatabaseHas('products', [
            'product_name' => 'Test Product',
            'product_type' => 'Electronics',
            'product_sku' => 'SKU12345',
            'product_description' => 'A great product',
            'price' => 1000,
            'created_by' => $this->user->id,
        ]);

        // Check if the files were stored and the file records exist in the database and response
        $product = Product::first();
        $responseData = $response->json('data');
        $responseFiles = $responseData['files'];

        foreach ($responseFiles as $responseFile) {
            // Storage::disk('public')->assertExists('files/' . '');
            $this->assertFileExists(storage_path('app/' . $responseFile['path']));
            // print_r(basename($responseFile['path']));
            $this->assertDatabaseHas('files', [
                'filename' => $responseFile['filename'],
                'related_id' => $product->id,
                'related_type' => Product::class,
            ]);
        }
    }

    /** @test */
    public function it_can_update_a_product_with_files()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $data = [
            'product_name' => 'Updated Product',
            'product_type' => 'Updated Type',
            'product_sku' => 'UPDATEDSKU',
            'product_description' => 'Updated Description',
            'price' => 5678,
            'files' => [
                UploadedFile::fake()->image('new_example1.jpg'),
                UploadedFile::fake()->image('new_example2.jpg'),
            ],
        ];

        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['product_name' => 'Updated Product'])
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'product_name',
                    'product_type',
                    'product_sku',
                    'product_label',
                    'product_description',
                    'product_barcode_id',
                    'price',
                    // 'created_by',
                    'created_at',
                    'updated_at',
                    'files' => [
                        '*' => [
                            'id',
                            'filename',
                            'path',
                            'url',
                            'uploaded_by',
                            'related_id',
                            'related_type',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                'message',
                'errors',
            ]);

        // Check if the product was updated in the database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_name' => 'Updated Product',
            'product_type' => 'Updated Type',
            'product_sku' => 'UPDATEDSKU',
            'product_description' => 'Updated Description',
            'price' => 5678,
        ]);

        // Check if the new files were stored and the file records exist in the database and response
        $responseData = $response->json('data');
        $responseFiles = $responseData['files'];

        // print_r($responseFiles);
        foreach ($responseFiles as $responseFile) {
            // $this->assertFileExists(storage_path('app/' . $file['path']));
            $this->assertFileExists(storage_path('app/' . $responseFile['path']));
            // Storage::disk('public')->assertExists('files/' . basename($responseFile['path']));
            $this->assertDatabaseHas('files', [
                'filename' => $responseFile['filename'],
                'related_id' => $product->id,
                'related_type' => Product::class,
            ]);
        }
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
