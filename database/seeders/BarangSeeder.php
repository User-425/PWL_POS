<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Supplier A
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Ponds Facial Foam',
                'harga_beli' => 15000,
                'harga_jual' => 20000,
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Garnier Micellar Water',
                'harga_beli' => 25000,
                'harga_jual' => 35000,
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Minyak Goreng Bimoli 1L',
                'harga_beli' => 12000,
                'harga_jual' => 15000,
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Gula Pasir Gulaku 1kg',
                'harga_beli' => 10000,
                'harga_jual' => 13000,
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Indomie Goreng',
                'harga_beli' => 2000,
                'harga_jual' => 2500,
            ],

            // Supplier B
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'SilverQueen Coklat',
                'harga_beli' => 15000,
                'harga_jual' => 20000,
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Oreo',
                'harga_beli' => 5000,
                'harga_jual' => 7000,
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Aqua 600ml',
                'harga_beli' => 3000,
                'harga_jual' => 4000,
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Teh Botol Sosro',
                'harga_beli' => 4000,
                'harga_jual' => 5000,
            ],
            [
                'kategori_id' => 5,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Pampers M34',
                'harga_beli' => 50000,
                'harga_jual' => 60000,
            ],

            // Supplier C
            [
                'kategori_id' => 5,
                'barang_kode' => 'BRG011',
                'barang_nama' => 'Susu Bebelac 3',
                'harga_beli' => 80000,
                'harga_jual' => 90000,
            ],
            [
                'kategori_id' => 1,
                'barang_kode' => 'BRG012',
                'barang_nama' => 'Wardah Lipstick',
                'harga_beli' => 30000,
                'harga_jual' => 40000,
            ],
            [
                'kategori_id' => 2,
                'barang_kode' => 'BRG013',
                'barang_nama' => 'Tepung Terigu Segitiga Biru 1kg',
                'harga_beli' => 8000,
                'harga_jual' => 10000,
            ],
            [
                'kategori_id' => 4,
                'barang_kode' => 'BRG014',
                'barang_nama' => 'Coca Cola 1.5L',
                'harga_beli' => 12000,
                'harga_jual' => 15000,
            ],
            [
                'kategori_id' => 3,
                'barang_kode' => 'BRG015',
                'barang_nama' => 'Chitato',
                'harga_beli' => 8000,
                'harga_jual' => 10000,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}
