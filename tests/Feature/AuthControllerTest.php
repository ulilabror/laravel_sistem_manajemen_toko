<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test register method.
     *
     * @return void
     */
    public function test_register()
    {
        Role::factory()->count(5)->create();

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'phone' => '123456789',
            'role_id' =>  Role::inRandomOrder()->first()->id,
        ]);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'phone',
                    'role',
                    'created_at',
                    'updated_at'
                ],
                'message',
                'errors'
            ]);
    }

    /**
     * Test login method.
     *
     * @return void
     */
    public function test_login()
    {
        // $role_id = Role::factory()->create()->id;
        Role::factory()->count(5)->create();
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => Hash::make('password'),
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'phone',
                        'role',
                        'created_at',
                        'updated_at'
                    ],
                    'authorization' => [
                        'token',
                        'type'
                    ]
                ],
                'message',
                'errors'
            ]);
    }

    /**
     * Test logout method.
     *
     * @return void
     */
    public function test_logout()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out',
            ]);
    }

    /**
     * Test refresh method.
     *
     * @return void
     */
    public function test_refresh()
    {
        Role::factory()->create();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/refresh');


        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'phone',
                        'role',
                        'created_at',
                        'updated_at'
                    ],
                    'authorization' => [
                        'token',
                        'type'
                    ]
                ],
                'message',
                'errors'
            ]);
    }
}
