<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'full_name' => 'Admin',
            'role_id' => 1,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'avatar' => 'https://i.ibb.co/pRN8MXC/468544534-1225284468769231-8158323027506095903-n.jpg',
            'status' => 1,
        ]);
    }
}
