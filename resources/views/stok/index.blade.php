@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <!-- Stock Overview Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $products->count() }}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $products->filter(function ($product) use ($stockData) {
        return isset($stockData[$product->barang_id]) && $stockData[$product->barang_id] > 10;
    })->count() }}</h3>
                    <p>Produk dengan Stok Baik</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $products->filter(function ($product) use ($stockData) {
        return isset($stockData[$product->barang_id]) && $stockData[$product->barang_id] <= 10 && $stockData[$product->barang_id] > 0;
    })->count() }}</h3>
                    <p>Produk Stok Rendah</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $products->filter(function ($product) use ($stockData) {
        return !isset($stockData[$product->barang_id]) || $stockData[$product->barang_id] <= 0;
    })->count() }}</h3>
                    <p>Produk Habis Stok</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stock Management Card -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cubes mr-1"></i> Manajemen Stok Produk
            </h3>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <div class="btn-group" id="filter-buttons">
                    <button type="button" class="btn btn-default" data-filter="all">Semua Produk</button>
                    <button type="button" class="btn btn-success" data-filter="in-stock">Tersedia</button>
                    <button type="button" class="btn btn-warning" data-filter="low-stock">Stok Rendah</button>
                    <button type="button" class="btn btn-danger" data-filter="out-of-stock">Habis Stok</button>
                </div>
            </div>

            <table id="stock-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th width="10%">Stok Saat Ini</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr
                        class="product-row {{ !isset($stockData[$product->barang_id]) || $stockData[$product->barang_id] <= 0 ? 'out-of-stock' : ($stockData[$product->barang_id] <= 10 ? 'low-stock' : 'in-stock') }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="badge badge-info">{{ $product->barang_kode }}</span>
                        </td>
                        <td>{{ $product->barang_nama }}</td>
                        <td>{{ $product->kategori->kategori_nama }}</td>
                        <td>Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge badge-pill badge-{{
                                                                        !isset($stockData[$product->barang_id]) || $stockData[$product->barang_id] <= 0 ? 'danger' :
                            ($stockData[$product->barang_id] <= 10 ? 'warning' : 'success')
                                                                    }} stock-badge">
                                {{ $stockData[$product->barang_id] ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if(!isset($stockData[$product->barang_id]) || $stockData[$product->barang_id] <= 0)
                                <span class="badge badge-danger">Habis Stok</span>
                                @elseif($stockData[$product->barang_id] <= 10)
                                    <span class="badge badge-warning">Stok Rendah</span>
                                    @else
                                    <span class="badge badge-success">Tersedia</span>
                                    @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info btn-history"
                                data-id="{{ $product->barang_id }}" data-name="{{ $product->barang_nama }}">
                                <i class="fas fa-history"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="modal-history">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-white">
                    <i class="fas fa-history mr-2"></i>Riwayat Stok: <span id="modal-product-name"></span>
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="history-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat riwayat stok...</p>
                </div>
                <div id="history-content" style="display: none;">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .stock-badge {
        font-size: 0.9rem;
        padding: 6px 10px;
    }

    .small-box .icon i {
        font-size: 65px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: transform 0.3s ease-in-out;
        opacity: 0.3;
    }

    .small-box:hover .icon i {
        transform: scale(1.1);
        opacity: 0.6;
    }

    #filter-buttons .btn {
        margin-right: 5px;
    }

    #filter-buttons .btn.active {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .product-row.filtered {
        display: none;
    }

    .badge {
        font-size: 90%;
    }
</style>
@endpush

@push('js')
<script>
    // Define the function first
    function showStockHistory(productId, productName) {
        $('#modal-product-name').text(productName);
        $('#modal-history').modal('show');
        $('#history-loading').show();
        $('#history-content').hide();
        $('#btn-export-history').hide();

        $.ajax({
            url: `{{ url('/stok/history') }}/${productId}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#history-loading').hide();

                if (response.success && response.html) {
                    // Insert the rendered HTML content into our modal
                    $('#history-content').html(response.html).show();

                    // Initialize DataTables within the modal
                    if ($.fn.DataTable.isDataTable('#historyTable')) {
                        $('#historyTable').DataTable().destroy();
                    }

                    $("#historyTable").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "order": [
                            [0, 'desc']
                        ],
                        "buttons": [{
                                extend: 'excel',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn-sm btn-success',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'pdf',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn-sm btn-danger',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Print',
                                className: 'btn-sm btn-info',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4, 5]
                                }
                            }
                        ]
                    }).buttons().container().appendTo('#historyTable_wrapper .col-md-6:eq(0)');

                    // Show export button if we have history data
                    $('#btn-export-history').show();
                } else {
                    $('#history-content').html(`
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <p>Tidak ada catatan riwayat untuk produk ini</p>
                            </div>
                        `).show();
                }
            },
            error: function() {
                $('#history-loading').hide();
                $('#history-content').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                            <p>Gagal memuat data riwayat stok</p>
                        </div>
                    `).show();
            }
        });
    }

    $(function() {
        // Initialize DataTable
        var table = $('#stock-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn-sm btn-success',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn-sm btn-danger',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn-sm btn-info',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }
            ]
        });

        table.buttons().container().appendTo('#stock-table_wrapper .col-md-6:eq(0)');

        $('#form-add-stock').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                barang_id: $('#product-id').val(),
                supplier_id: $('#supplier-id').val(),
                stok_jumlah: $('#stock-quantity').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $('#btn-save-stock').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: '{{ url(' / stok ') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#modal-add-stock').modal('hide');

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Stok berhasil ditambahkan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Gagal menambahkan stok',
                            icon: 'error'
                        });
                    }
                    $('#btn-save-stock').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan yang tidak terduga',
                        icon: 'error'
                    });
                    $('#btn-save-stock').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan');
                }
            });
        });

        $('.btn-history').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            showStockHistory(id, name);
        });

        // Filter buttons
        $('#filter-buttons button').on('click', function() {
            var filter = $(this).data('filter');

            $('#filter-buttons button').removeClass('active');
            $(this).addClass('active');

            $('.product-row').removeClass('filtered');

            if (filter !== 'all') {
                $('.product-row:not(.' + filter + ')').addClass('filtered');
            }

            table.draw();
        });

        $('#filter-buttons button:first').addClass('active');
    });
</script>
@endpush
