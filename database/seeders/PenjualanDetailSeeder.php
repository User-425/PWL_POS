<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Detail untuk penjualan 1
            [
                'penjualan_id' => 1,
                'barang_id' => 1,
                'harga' => 20000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 1,
                'barang_id' => 2,
                'harga' => 35000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 1,
                'barang_id' => 3,
                'harga' => 15000,
                'jumlah' => 3,
            ],

            // Detail untuk penjualan 2
            [
                'penjualan_id' => 2,
                'barang_id' => 4,
                'harga' => 13000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 2,
                'barang_id' => 5,
                'harga' => 2500,
                'jumlah' => 5,
            ],
            [
                'penjualan_id' => 2,
                'barang_id' => 6,
                'harga' => 20000,
                'jumlah' => 1,
            ],

            // Detail untuk penjualan 3
            [
                'penjualan_id' => 3,
                'barang_id' => 7,
                'harga' => 7000,
                'jumlah' => 3,
            ],
            [
                'penjualan_id' => 3,
                'barang_id' => 8,
                'harga' => 4000,
                'jumlah' => 4,
            ],
            [
                'penjualan_id' => 3,
                'barang_id' => 9,
                'harga' => 5000,
                'jumlah' => 2,
            ],

            // Detail untuk penjualan 4
            [
                'penjualan_id' => 4,
                'barang_id' => 10,
                'harga' => 60000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 4,
                'barang_id' => 11,
                'harga' => 90000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 4,
                'barang_id' => 12,
                'harga' => 40000,
                'jumlah' => 2,
            ],

            // Detail untuk penjualan 5
            [
                'penjualan_id' => 5,
                'barang_id' => 13,
                'harga' => 10000,
                'jumlah' => 3,
            ],
            [
                'penjualan_id' => 5,
                'barang_id' => 14,
                'harga' => 15000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 5,
                'barang_id' => 15,
                'harga' => 10000,
                'jumlah' => 4,
            ],

            // Detail untuk penjualan 6
            [
                'penjualan_id' => 6,
                'barang_id' => 1,
                'harga' => 20000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 6,
                'barang_id' => 2,
                'harga' => 35000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 6,
                'barang_id' => 3,
                'harga' => 15000,
                'jumlah' => 3,
            ],

            // Detail untuk penjualan 7
            [
                'penjualan_id' => 7,
                'barang_id' => 4,
                'harga' => 13000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 7,
                'barang_id' => 5,
                'harga' => 2500,
                'jumlah' => 4,
            ],
            [
                'penjualan_id' => 7,
                'barang_id' => 6,
                'harga' => 20000,
                'jumlah' => 2,
            ],

            // Detail untuk penjualan 8
            [
                'penjualan_id' => 8,
                'barang_id' => 7,
                'harga' => 7000,
                'jumlah' => 3,
            ],
            [
                'penjualan_id' => 8,
                'barang_id' => 8,
                'harga' => 4000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 8,
                'barang_id' => 9,
                'harga' => 5000,
                'jumlah' => 4,
            ],

            // Detail untuk penjualan 9
            [
                'penjualan_id' => 9,
                'barang_id' => 10,
                'harga' => 60000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 9,
                'barang_id' => 11,
                'harga' => 90000,
                'jumlah' => 1,
            ],
            [
                'penjualan_id' => 9,
                'barang_id' => 12,
                'harga' => 40000,
                'jumlah' => 2,
            ],

            // Detail untuk penjualan 10
            [
                'penjualan_id' => 10,
                'barang_id' => 13,
                'harga' => 10000,
                'jumlah' => 3,
            ],
            [
                'penjualan_id' => 10,
                'barang_id' => 14,
                'harga' => 15000,
                'jumlah' => 2,
            ],
            [
                'penjualan_id' => 10,
                'barang_id' => 15,
                'harga' => 10000,
                'jumlah' => 4,
            ],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
