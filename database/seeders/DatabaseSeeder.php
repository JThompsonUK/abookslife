<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('Database\Seeders\UserSeeder');
        $this->call('Database\Seeders\BookSeeder');
        $this->call('Database\Seeders\BookCheckoutSeeder');
        $this->call('Database\Seeders\BookCommentsSeeder');

    }
}
