@extends('layouts.template')

@section('content')
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-shopping-cart"></i> Transaksi Pembelian
                <small class="float-right">Tanggal: {{ \Carbon\Carbon::parse($pembelian->pembelian_tanggal)->format('d/m/Y H:i') }}</small>
            </h4>
        </div>
    </div>
    
    <div class="row invoice-info mt-3">
        <div class="col-sm-4 invoice-col">
            <address>
                <strong>Supplier:</strong><br>
                {{ $pembelian->supplier->supplier_nama }}<br>
                {{ $pembelian->supplier->supplier_alamat }}<br>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <address>
                <strong>Informasi Transaksi</strong><br>
                <b>Kode Pembelian:</b> {{ $pembelian->pembelian_kode }}<br>
                <b>Tanggal:</b> {{ \Carbon\Carbon::parse($pembelian->pembelian_tanggal)->format('d/m/Y H:i') }}<br>
                <b>Petugas:</b> {{ $pembelian->user->nama }}
            </address>
        </div>
    </div>

    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; $no = 1; @endphp
                    @foreach($pembelian->details as $detail)
                    @php 
                        $subtotal = $detail->harga * $detail->jumlah;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->barang->barang_kode }}</td>
                        <td>{{ $detail->barang->barang_nama }}</td>
                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total:</th>
                        <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row no-print">
        <div class="col-12">
            <a href="{{ url('pembelian/' . $pembelian->pembelian_id) }}/receipt" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Faktur
            </a>
            <a href="{{ url('pembelian') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection