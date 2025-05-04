@extends('layouts.template')

@section('content')
<!-- Welcome Message -->
<div class="row mb-2">
    <div class="col-12">
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> Selamat Datang!</h5>
            <p>Selamat datang di sistem Point of Sale. Gunakan menu di sidebar untuk navigasi.</p>
        </div>
    </div>
</div>

<!-- Statistics Summary -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="total-transactions">0</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ url('/transaksi') }}" class="small-box-footer">
                Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="total-revenue">Rp 0</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ url('/transaksi') }}" class="small-box-footer">
                Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="today-transactions">0</h3>
                <p>Transaksi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <a href="{{ url('/transaksi') }}" class="small-box-footer">
                Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="total-items">0</h3>
                <p>Total Barang Terjual</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ url('/barang') }}" class="small-box-footer">
                Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Charts and Data Section -->
<div class="row">
    <!-- Sales Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Grafik Penjualan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative mb-4">
                    <canvas id="sales-chart" height="300"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2">
                        <i class="fas fa-square text-primary"></i> Minggu Ini
                    </span>
                    <span>
                        <i class="fas fa-square text-gray"></i> Minggu Lalu
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Stok Menipis</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="low-stock-table">
                            <tr>
                                <td colspan="4" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaksi Penjualan Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pembeli</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="recent-sales">
                            <tr>
                                <td colspan="4" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ url('/transaksi') }}" class="text-muted">Lihat Semua Transaksi</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaksi Pembelian Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="recent-purchases">
                            <tr>
                                <td colspan="4" class="text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ url('/pembelian') }}" class="text-muted">Lihat Semua Pembelian</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/transaksi/create') }}" class="btn btn-app bg-success">
                            <i class="fas fa-cart-plus"></i> Transaksi Baru
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/pembelian/create') }}" class="btn btn-app bg-primary">
                            <i class="fas fa-truck-loading"></i> Pembelian Baru
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/barang') }}" class="btn btn-app bg-warning">
                            <i class="fas fa-box-open"></i> Kelola Barang
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/stok') }}" class="btn btn-app bg-info">
                            <i class="fas fa-boxes"></i> Kelola Stok
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Load dashboard statistics
    loadStatistics();
    loadLowStockItems();
    loadRecentTransactions();
    loadSalesChart();

    // Refresh data every 5 minutes
    setInterval(function() {
        loadStatistics();
        loadLowStockItems();
        loadRecentTransactions();
    }, 300000);

    function loadStatistics() {
        $.ajax({
            url: '{{ url("/api/stats") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-transactions').text(data.total_transactions);
                $('#total-revenue').text('Rp ' + data.total_revenue);
                $('#today-transactions').text(data.today_transactions);
                $('#total-items').text(data.total_items);
            },
            error: function(xhr, status, error) {
                console.error("Couldn't load statistics: " + error);
            }
        });
    }

    function loadLowStockItems() {
        $.ajax({
            url: '{{ url("/api/stock/low") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.items && data.items.length > 0) {
                    $.each(data.items, function(index, item) {
                        let statusClass = item.stock < 5 ? 'danger' : 'warning';
                        html += `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.stock}</td>
                            <td><span class="badge badge-${statusClass}">Stok Menipis</span></td>
                            <td><a href="{{ url('/stok/history/') }}/${item.id}" class="btn btn-sm btn-info">Detail</a></td>
                        </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center">Tidak ada produk dengan stok menipis</td></tr>';
                }
                $('#low-stock-table').html(html);
            },
            error: function(xhr, status, error) {
                $('#low-stock-table').html('<tr><td colspan="4" class="text-center">Gagal memuat data</td></tr>');
                console.error("Couldn't load low stock items: " + error);
            }
        });
    }

    function loadRecentTransactions() {
        // Load recent sales
        $.ajax({
            url: '{{ url("/api/transactions/recent") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.transactions && data.transactions.length > 0) {
                    $.each(data.transactions, function(index, item) {
                        html += `
                        <tr>
                            <td><a href="{{ url('/transaksi') }}/${item.id}">${item.code}</a></td>
                            <td>${item.customer}</td>
                            <td>Rp ${item.total}</td>
                            <td>${item.date}</td>
                        </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center">Tidak ada transaksi terbaru</td></tr>';
                }
                $('#recent-sales').html(html);
            },
            error: function(xhr, status, error) {
                $('#recent-sales').html('<tr><td colspan="4" class="text-center">Gagal memuat data</td></tr>');
                console.error("Couldn't load recent sales: " + error);
            }
        });

        // Load recent purchases
        $.ajax({
            url: '{{ url("/api/purchases/recent") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.purchases && data.purchases.length > 0) {
                    $.each(data.purchases, function(index, item) {
                        html += `
                        <tr>
                            <td><a href="{{ url('/pembelian') }}/${item.id}">${item.code}</a></td>
                            <td>${item.supplier}</td>
                            <td>Rp ${item.total}</td>
                            <td>${item.date}</td>
                        </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center">Tidak ada pembelian terbaru</td></tr>';
                }
                $('#recent-purchases').html(html);
            },
            error: function(xhr, status, error) {
                $('#recent-purchases').html('<tr><td colspan="4" class="text-center">Gagal memuat data</td></tr>');
                console.error("Couldn't load recent purchases: " + error);
            }
        });
    }

    function loadSalesChart() {
        // For demonstration purpose, we'll use dummy data
        // In a real-world scenario, you would fetch this from an API

        const salesChartCanvas = document.getElementById('sales-chart').getContext('2d');

        // Sample data for the last 7 days
        const currentWeekData = [7500000, 5900000, 8100000, 8600000, 7400000, 9100000, 9600000];
        const previousWeekData = [6900000, 5200000, 7800000, 8100000, 6800000, 7900000, 8800000];
        const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Format data for display (in millions)
        const currentWeekDataDisplay = currentWeekData.map(value => value / 1000000);
        const previousWeekDataDisplay = previousWeekData.map(value => value / 1000000);

        const salesChart = new Chart(salesChartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Minggu Ini',
                        data: currentWeekDataDisplay,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: true
                    },
                    {
                        label: 'Minggu Lalu',
                        data: previousWeekDataDisplay,
                        borderColor: '#ced4da',
                        backgroundColor: 'rgba(210, 214, 222, 0.1)',
                        pointBorderColor: '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill: true
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ': Rp ' + tooltipItem.yLabel.toFixed(1) + ' jt';
                        }
                    }
                },
                hover: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value + ' jt';
                            }
                        }
                    }]
                }
            }
        });
    }
});
</script>
@endpush

@push('css')
<style>
.btn-app {
    height: 80px;
    width: 100%;
    padding: 25px 5px;
    font-size: 16px;
    margin-bottom: 10px;
}

.btn-app i {
    font-size: 24px;
    display: block;
    margin-bottom: 8px;
}
</style>
@endpush
