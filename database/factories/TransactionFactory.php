<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'transaction_type' => $this->faker->randomElement(['purchase', 'refund']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'debit_card', 'paypal']),
            'payment_id' => Str::upper(Str::random(10)),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'amount' => $this->faker->numberBetween(100, 10000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'product_price' => $this->faker->numberBetween(100, 10000),
        ];
    }
}
