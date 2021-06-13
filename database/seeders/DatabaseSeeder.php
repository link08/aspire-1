<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Loan;
use App\Models\EmiTransaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            ['name' => 'Test User', 'email' => 'test@test.com', 'password' => 'pass'],
            ['name' => 'Demo User', 'email' => 'demo@demo.com', 'password' => 'demo'],
            ['name' => 'Aspire User', 'email' => 'aspire@aspire.com', 'password' => 'aspire']
        ];

        // Insert users and their few loans
        foreach($userData as $newUser) {
            $user = new User;
            $user->name     = $newUser['name'];
            $user->email    = $newUser['email'];
            $user->password = Hash::make($newUser['password']);
            $user->save();

            // Insert 4 loans for each user
            for($i = 0; $i < 4; $i++) {
                $loan = new Loan;
                $loan->amount = rand(10, 100) * 100;
                $loan->amount_paid      = 0;
                $loan->loan_term        = rand(7, 500);
                $loan->repayment_type   = ['WEEKLY', 'MONTHLY', 'ANNUALLY'][$i % 3];
                $loan->interest_rate    = config('app.loan_interest_rate');
                $loan->status           = 'PENDING';
                $loan->user()->associate($user);
                $loan->save();
            }
        }
    }
}
