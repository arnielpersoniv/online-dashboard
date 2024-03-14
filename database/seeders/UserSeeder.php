<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'emp_id'        => '6072021',
            'name'          => 'ITDev Admin',
            'email'         => 'itdev.admin@personiv.com',
            'role'          => 'admin',
            'password'      => '$2y$10$abaQE09KysUjikmz62U6OO46ccH6RGXnM0Zf.f1dTsc75EasMWa1G',//!Welcome18
        ];

        User::create($data);
    }
}
