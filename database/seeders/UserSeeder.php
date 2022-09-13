<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'id'    => 1,
                'role' => 1,
                'name' => 'Admin',
                'email' => 'admin@el.itera.ac.id',
                'password' => Hash::make('password'),
                'inviteable' => 0,
            ],
        ];

        User::insert($user);
    }
}
