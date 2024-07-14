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
            ->assertJsonCount(10);
    }

    public function test_can_create_file()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $response = $this->postJson('/api/files', [
            'filename' => 'product_image.jpg',
            'path' => UploadedFile::fake()->image('product_image.jpg'),
            'uploaded_by' => $this->user->id,
            'related_id' => $product->id,
            'related_type' => 'App\Models\Product',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'file' => [
                    'id',
                    'filename',
                    'path',
                    'uploaded_by',
                    'related_id',
                    'related_type',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $filePath = str_replace('public/', '', $response->json('file.path'));
        $this->assertFileExists(storage_path('app/public/' . $filePath));
    }

    public function test_can_show_file()
    {
        $file = File::factory()->create();

        $response = $this->getJson('/api/files/' . $file->id);

        $response->assertStatus(200)
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
            ->assertJsonFragment([
                'filename' => $updatedData['filename'],
            ]);

        $this->assertDatabaseHas('files', $updatedData);
    }

    public function test_can_delete_file()
    {
        $file = File::factory()->create();

        $response = $this->deleteJson('/api/files/' . $file->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'File deleted successfully',
            ]);

        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }
}
