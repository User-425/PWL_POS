<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 3,
                'pembeli' => 'Sudirman',
                'penjualan_kode' => 'PNJ001',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Jane',
                'penjualan_kode' => 'PNJ002',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Alice',
                'penjualan_kode' => 'PNJ003',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Bob',
                'penjualan_kode' => 'PNJ004',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Charlie',
                'penjualan_kode' => 'PNJ005',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Ruth',
                'penjualan_kode' => 'PNJ006',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Alexia',
                'penjualan_kode' => 'PNJ007',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Mary Jane',
                'penjualan_kode' => 'PNJ008',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Bilqis',
                'penjualan_kode' => 'PNJ009',
                'penjualan_tanggal' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Aliyah',
                'penjualan_kode' => 'PNJ010',
                'penjualan_tanggal' => now(),
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
