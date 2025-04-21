<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BarangModel extends Model
{
    use HasFactory;
    
    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    
    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function getCurrentStock()
    {
        $stockIn = DB::table('t_stok')
            ->where('barang_id', $this->barang_id)
            ->where('stok_jumlah', '>', 0)
            ->sum('stok_jumlah');
        
        $stockOut = DB::table('t_penjualan_detail')
            ->where('barang_id', $this->barang_id)
            ->sum('jumlah');
        
        return $stockIn - $stockOut;
    }
}