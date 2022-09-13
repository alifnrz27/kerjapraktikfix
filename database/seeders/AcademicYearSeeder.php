<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yearAcademic = [
            [
                'id'    => 1,
                'semester' => 2,
                'year' => '2022/2023',
                'status' => 0,
            ],
            [
                'id'    => 2,
                'semester' => 1,
                'year' => '2023/2024',
                'status' => 1,
            ],
        ];

        AcademicYear::insert($yearAcademic);
    }
}
