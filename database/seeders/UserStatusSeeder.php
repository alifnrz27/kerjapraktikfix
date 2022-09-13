<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userStatus = [
            [
                'id'    => 1,
                'name' => 'active',
            ],
            [
                'id'    => 2,
                'name' => 'passed',
            ],
        ];

        UserStatus::insert($userStatus);
    }
}
