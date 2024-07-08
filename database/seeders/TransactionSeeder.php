<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure that we have users to associate transactions with
        if (User::count() == 0) {
            $this->command->info('No users found, skipping transactions seeder');
            return;
        }

        // Generate 10 sample transactions for each user
        User::all()->each(function ($user) {
            for ($i = 0; $i < 10; $i++) {
                Transaction::create([
                    'user_id' => $user->id,
                    'transaction_type' => $this->getRandomTransactionType(),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'transaction_date' => now()->subDays(rand(1, 365)),
                    'amount' => rand(100, 10000),
                ]);
            }
        });
    }

    /**
     * Get a random transaction type.
     *
     * @return string
     */
    private function getRandomTransactionType()
    {
        $types = ['purchase', 'refund', 'withdrawal', 'deposit'];
        return $types[array_rand($types)];
    }

    /**
     * Get a random payment method.
     *
     * @return string
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash'];
        return $methods[array_rand($methods)];
    }
}
