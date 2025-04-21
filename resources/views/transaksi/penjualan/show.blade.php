@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Transaksi {{ $transaction->penjualan_kode }}</h3>
        <div class="card-tools">
            <a href="{{ url('transaksi') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ url('transaksi/'.$transaction->penjualan_id.'/receipt') }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px">Kode Transaksi</th>
                        <td>: {{ $transaction->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: {{ \Carbon\Carbon::parse($transaction->penjualan_tanggal)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Kasir</th>
                        <td>: {{ $transaction->user->nama }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px">Pembeli</th>
                        <td>: {{ $transaction->pembeli ?? 'Umum' }}</td>
                    </tr>
                    <tr>
                        <th>Total Item</th>
                        <td>: {{ $transaction->details->sum('jumlah') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($transaction->details as $index => $detail)
                        @php 
                            $subtotal = $detail->harga * $detail->jumlah;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->barang->barang_nama }}</td>
                            <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $detail->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <th class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Additional scripts if needed
    });
</script>
@endpush