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
        User::firstOrCreate([
            'name' => 'John Smith',
            'email' => 'johnsmith@test.com',
        ], [
            'email_verified_at' => now(),
            'password' => 'test'
        ]);

    $user = User::factory()->count(20)->create();

    }
}
