<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        User::factory()->create([5]);

        User::firstOrNew([
            'name' => 'Jamie Thompson',
            'email' => 'jkthompson@hotmail.co.uk',
            'email_verified_at' => now(),
            'password' => 'test'
        ]);

    }
}
