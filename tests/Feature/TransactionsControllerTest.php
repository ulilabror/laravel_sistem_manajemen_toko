<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        Role::factory()->count(4)->create();
        // Create a user and log in
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

        // Create a product
        $this->product = Product::factory(['created_by' => $this->user->id])->create();
    }

    /** @test */
    public function it_can_list_transactions()
    {
        Transaction::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'product_price' => $this->product->price
        ]);

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'product_id',
                        'transaction_type',
                        'payment_method',
                        'payment_id',
                        'transaction_date',
                        'amount',
                        'quantity',
                        'product_price',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'message',
                'errors',
            ])->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_transaction()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'product_price' => $this->product->price,
        ]);

        $response = $this->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $transaction->id,
            ])
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'user_id',
                    'product_id',
                    'product_price',
                    'transaction_type',
                    'payment_method',
                    'payment_id',
                    'transaction_date',
                    'amount',
                    'quantity',
                    'product_price',
                    'created_at',
                    'updated_at',
                ],
                'message',
                'errors',
            ]);
    }

    /** @test */
    public function it_can_create_a_transaction()
    {
        $data = [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'transaction_type' => 'purchase',
            'payment_method' => 'credit_card',
            'payment_id' => 'PAY123456789',
            'transaction_date' => now()->toIso8601String(),
            'amount' => 1000,
            'quantity' => 1,
            'product_price' => 1000,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'user_id',
                    'product_id',
                    'transaction_type',
                    'payment_method',
                    'payment_id',
                    'transaction_date',
                    'amount',
                    'quantity',
                    'product_price',
                    'created_at',
                    'updated_at',
                ],
                'message',
                'errors',
            ]);

        $this->assertDatabaseHas('transactions', $data);
    }

    /** @test */
    public function it_can_update_a_transaction()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'product_price' => $this->product->price,
        ]);

        $data = [
            'transaction_type' => 'purchase_updated',
            'payment_method' => 'debit_card',
            'payment_id' => 'PAY123456789',
            'transaction_date' => now()->toIso8601String(),
            'amount' => 1500,
            'quantity' => 2,
            'product_price' => 1000,
        ];

        $response = $this->putJson("/api/transactions/{$transaction->id}", $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'user_id',
                    'product_id',
                    'transaction_type',
                    'payment_method',
                    'payment_id',
                    'transaction_date',
                    'amount',
                    'quantity',
                    'product_price',
                    'created_at',
                    'updated_at',
                ],
                'message',
                'errors',
            ]);

        $this->assertDatabaseHas('transactions', $data);
    }

    /** @test */
    public function it_can_delete_a_transaction()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->deleteJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Transaction deleted successfully',
            ]);

        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
