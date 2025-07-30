<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\reqOffice;

class ReqOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            'ISDU',
            'NMU',
            'REPAIR',
            'MB',
            'CEISSAFP',
        ];

        foreach ($offices as $office) {
            reqOffice::firstOrCreate(['req_office' => $office]);
        }
    }
}
