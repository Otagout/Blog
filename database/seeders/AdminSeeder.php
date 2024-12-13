<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create a default admin user
        Admin::create([
            'name' => 'Admin  ',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456789'), // Use bcrypt() to hash passwords
        ]);

      }
}
