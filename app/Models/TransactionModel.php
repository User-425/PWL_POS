<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionModel extends Model
{
    use HasFactory;
    
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';
    
    protected $fillable = [
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetailModel::class, 'penjualan_id', 'penjualan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function getTotal()
    {
        return $this->details->sum(function($detail) {
            return $detail->harga * $detail->jumlah;
        });
    }
}