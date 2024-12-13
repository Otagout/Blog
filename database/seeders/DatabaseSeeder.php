<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // \App\Models\User::factory(10)->create();

     /*  \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'oussama@gmail.com',
              'password' => Hash::make('123456789'),
         ]);
         */
         \App\Models\Admin::factory()->create([
          'name' => 'Admin',
          'email' => 'admin@admin.com',
           'password' => Hash::make('123456789'),
      ]);
    }
}
