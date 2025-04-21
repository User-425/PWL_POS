<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $transaction->penjualan_kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .receipt {
            max-width: 80mm;
            margin: 0 auto;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .receipt-header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .receipt-details {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .receipt-details p {
            margin: 3px 0;
        }
        .receipt-details .label {
            font-weight: bold;
            display: inline-block;
            width: 90px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            text-align: left;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .totals .line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .totals .grand-total {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1>Indomidi</h1>
            <p>Jl. Soekarno Hatta No. 123</p>
            <p>Telp: (031) 123456789</p>
            <p>Email: info@indomidi.com</p>
        </div>

        <div class="receipt-details">
            <p><span class="label">No. Transaksi:</span> {{ $transaction->penjualan_kode }}</p>
            <p><span class="label">Tanggal:</span> {{ \Carbon\Carbon::parse($transaction->penjualan_tanggal)->format('d/m/Y H:i') }}</p>
            <p><span class="label">Kasir:</span> {{ $transaction->user->nama ?? 'Unknown' }}</p>
            <p><span class="label">Pembeli:</span> {{ $transaction->pembeli ?? 'Umum' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="45%">Item</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="15%" class="text-right">Qty</th>
                    <th width="25%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($transaction->details as $detail)
                    @php 
                        $subtotal = $detail->harga * $detail->jumlah;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $detail->barang->barang_nama ?? 'Unknown Item' }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->jumlah }}</td>
                        <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="line">
                <span>Total Items:</span>
                <span>{{ $transaction->details->sum('jumlah') }}</span>
            </div>
            <div class="line grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar kembali</p>
            <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>