<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetailModel extends Model
{
    use HasFactory;

    protected $table = 't_pembelian_detail';
    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'harga',
        'jumlah',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianModel::class, 'pembelian_id', 'pembelian_id');
    }

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}