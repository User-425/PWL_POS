<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faktur Pembelian {{ $pembelian->pembelian_kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px;
        }
        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f3f3f3;
            font-weight: bold;
        }
        .total-row td {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }
        .signature {
            margin-top: 30px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            border: none;
            vertical-align: top;
            padding: 10px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 80%;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>FAKTUR PEMBELIAN</h2>
        <p>{{ config('app.name', 'POS System') }}</p>
    </div>
    
    <div class="info">
        <div class="info-section">
            <table>
                <tr>
                    <td><strong>Kode Faktur</strong></td>
                    <td>: {{ $pembelian->pembelian_kode }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($pembelian->pembelian_tanggal)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Petugas</strong></td>
                    <td>: {{ $pembelian->user->nama }}</td>
                </tr>
            </table>
        </div>
        
        <div class="info-section">
            <table>
                <tr>
                    <td><strong>Supplier</strong></td>
                    <td>: {{ $pembelian->supplier->supplier_nama }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>: {{ $pembelian->supplier->supplier_alamat }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Kode Barang</th>
                <th style="width: 35%">Nama Barang</th>
                <th style="width: 15%">Harga</th>
                <th style="width: 10%">Jumlah</th>
                <th style="width: 20%">Subtotal</th>
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
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total:</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="signature">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Penerima:</p>
                    <div class="signature-line"></div>
                    <p>({{ $pembelian->supplier->supplier_nama }})</p>
                </td>
                <td>
                    <p>Petugas:</p>
                    <div class="signature-line"></div>
                    <p>{{ $pembelian->user->nama }}</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->format('d/m/Y H:i:s') }} dan merupakan bukti sah transaksi pembelian.</p>
    </div>
</body>
</html>