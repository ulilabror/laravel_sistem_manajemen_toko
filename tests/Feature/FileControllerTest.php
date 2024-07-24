<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\File;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileTest extends TestCase
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

    public function test_can_list_files()
    {
        File::factory()->count(10)->create();

        $response = $this->getJson('/api/files');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
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
                    ]
                ],
                'message',
            ])
            ->assertJsonCount(10, 'data');
    }

    public function test_can_create_file()
    {
        Storage::fake('public');

        $product = Product::factory()->create();
        $files = [UploadedFile::fake()->image('product_image.jpg'), UploadedFile::fake()->image('product_image.jpg')];
        $response = $this->postJson('/api/files', [
            'filename' => 'product_image.jpg',
            'files' => $files,
            'uploaded_by' => $this->user->id,
            'related_id' => $product->id,
            'related_type' => 'App\Models\Product',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
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
                    ]
                ],
                'message',
            ]);

        foreach ($response->json('data') as $file) {
            $filePath = str_replace('public/', '', $file['path']);
            // print($file['path']);
            $this->assertFileExists(storage_path('app/' . $file['path']));
        }
    }

    public function test_can_show_file()
    {
        $file = File::factory()->create();

        $response = $this->getJson('/api/files/' . $file->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
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
                'message',
            ])
            ->assertJsonFragment([
                'filename' => $file->filename,
            ]);
    }

    public function test_can_update_file()
    {
        $file = File::factory()->create();
        $updatedData = [
            'filename' => 'updated_file.jpg',
            'uploaded_by' => $this->user->id,
        ];

        $response = $this->putJson('/api/files/' . $file->id, $updatedData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
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
                'message',
            ])
            ->assertJsonFragment([
                'filename' => $updatedData['filename'],
            ]);

        $this->assertDatabaseHas('files', ['filename' => $updatedData['filename']]);
    }

    public function test_can_delete_file()
    {
        $file = File::factory()->create();

        $response = $this->deleteJson('/api/files/' . $file->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertJsonFragment([
                'message' => 'File deleted successfully',
            ]);

        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }
}
