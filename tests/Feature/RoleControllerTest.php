<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_roles()
    {
        Role::factory()->count(5)->create();

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_create_a_role()
    {
        $roleData = [
            'role_name' => 'Testing',
            'access_rights' => '["create", "update", "delete"]',
        ];

        $response = $this->postJson('/api/roles', $roleData);

        $response->assertStatus(201);
        $response->assertJsonFragment($roleData);
        $this->assertDatabaseHas('roles', $roleData);
    }

    /** @test */
    public function it_can_show_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson('/api/roles/' . $role->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'role_name' => $role->role_name,
            'access_rights' => $role->access_rights,
        ]);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $role = Role::factory()->create();

        $updateData = [
            'role_name' => 'Updated Role Name',
            'access_rights' => '["create", "read", "update", "delete"]',
        ];

        $response = $this->putJson('/api/roles/' . $role->id, $updateData);

        $response->assertStatus(200);
        $response->assertJsonFragment($updateData);
        $this->assertDatabaseHas('roles', $updateData);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson('/api/roles/' . $role->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test */
    public function it_validates_role_creation()
    {
        $response = $this->postJson('/api/roles', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['role_name', 'access_rights']);
    }

    /** @test */
    public function it_returns_404_if_role_not_found()
    {
        $response = $this->getJson('/api/roles/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Role not found']);
    }
}
