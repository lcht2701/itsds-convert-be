<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Customer
        User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => bcrypt('customer123'),
            'email_verified_at' => time(),
            'role' => 0,
        ]);

        //Company Admin
        User::factory()->create([
            'name' => 'Company Admin',
            'email' => 'companyAdmin@gmail.com',
            'password' => bcrypt('companyAdmin123'),
            'email_verified_at' => time(),
            'role' => 1,
        ]);
        User::factory()->create([
            'name' => 'Technician',
            'email' => 'technician@gmail.com',
            'password' => bcrypt('technician123'),
            'email_verified_at' => time(),
            'role' => 2,
        ]);
        User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('manager123'),
            'email_verified_at' => time(),
            'role' => 3,
        ]);

        //Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => time(),
            'role' => 4,
        ]);
    }
}
