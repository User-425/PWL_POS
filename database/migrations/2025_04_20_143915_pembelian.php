<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_pembelian', function (Blueprint $table) {
            $table->id('pembelian_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('pembelian_kode', 20);
            $table->dateTime('pembelian_tanggal');
            $table->timestamps();
            
            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('supplier_id')->references('supplier_id')->on('m_supplier');
        });

        Schema::create('t_pembelian_detail', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('pembelian_id');
            $table->unsignedBigInteger('barang_id');
            $table->decimal('harga', 10, 2);
            $table->integer('jumlah');
            $table->timestamps();
            
            $table->foreign('pembelian_id')->references('pembelian_id')->on('t_pembelian');
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pembelian_detail');
        Schema::dropIfExists('t_pembelian');
    }
};