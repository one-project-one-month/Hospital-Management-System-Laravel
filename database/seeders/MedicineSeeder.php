<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medicine::create([
            'name'=>'medicine1',
            'stock'=>30,
            'expired_at'=>'2024-2-2',
            'price'=>3000
        ]);

        Medicine::create([
            'name'=>'medicine5',
            'stock'=>30,
            'expired_at'=>'2024-2-2',
            'price'=>3000
        ]);

        Medicine::create([
            'name'=>'medicine4',
            'stock'=>30,
            'expired_at'=>'2024-2-2',
            'price'=>3000
        ]);

        Medicine::create([
            'name'=>'medicine3',
            'stock'=>30,
            'expired_at'=>'2024-2-2',
            'price'=>3000
        ]);

        Medicine::create([
            'name'=>'medicine2',
            'stock'=>30,
            'price'=>3000,
            "expired_at"=>'2024-2-2'
        ]);
    }
}
