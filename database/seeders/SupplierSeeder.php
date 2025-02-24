<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'Supplier A',
                'supplier_alamat' => 'Jalan Mawar No. 1',
            ],
            [
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'Supplier B',
                'supplier_alamat' => 'Jalan Melati No. 2',
            ],
            [
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'Supplier C',
                'supplier_alamat' => 'Jalan Anggrek No. 3',
            ],
        ];

        DB::table('m_supplier')->insert($data);
    }
}
