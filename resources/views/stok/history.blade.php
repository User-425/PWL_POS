
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $breadcrumb->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @foreach ($breadcrumb->list as $key => $item)
                            @if ($key == count($breadcrumb->list) - 1)
                                <li class="breadcrumb-item active">{{ $item }}</li>
                            @else
                                <li class="breadcrumb-item">{{ $item }}</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Product Info Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $page->title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 200px">ID Barang</th>
                                            <td>{{ $barang->barang_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <td>{{ $barang->barang_kode }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <td>{{ $barang->barang_nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kategori</th>
                                            <td>{{ $barang->kategori->kategori_nama ?? 'Tidak ada kategori' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="text-center mb-3">Stok Tersedia</h4>
                                            <h2 class="text-center text-primary">{{ $currentStock }}</h2>
                                            <p class="text-center text-muted">unit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- History Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Perubahan Stok</h3>
                        </div>
                        <div class="card-body">
                            <table id="historyTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Pihak</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $item)
                                        <tr class="{{ $item['is_incoming'] ? 'table-success' : 'table-danger' }}">
                                            <td>{{ $item['tanggal']->format('d M Y H:i') }}</td>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>
                                                @if ($item['is_incoming'])
                                                    <span class="badge badge-success">{{ $item['tipe'] }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $item['tipe'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item['is_incoming'])
                                                    <strong class="text-success">+{{ $item['jumlah'] }}</strong>
                                                @else
                                                    <strong class="text-danger">-{{ $item['jumlah'] }}</strong>
                                                @endif
                                            </td>
                                            <td>Rp{{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                                            <td>{{ $item['pihak'] }}</td>
                                            <td>{{ $item['petugas'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>