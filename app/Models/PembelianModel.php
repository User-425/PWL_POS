<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembelianModel extends Model
{
    use HasFactory;
    
    protected $table = 't_pembelian';
    protected $primaryKey = 'pembelian_id';
    
    protected $fillable = [
        'user_id',
        'supplier_id',
        'pembelian_kode',
        'pembelian_tanggal'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PembelianDetailModel::class, 'pembelian_id', 'pembelian_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    public function getTotal()
    {
        return $this->details->sum(function($detail) {
            return $detail->harga * $detail->jumlah;
        });
    }
}