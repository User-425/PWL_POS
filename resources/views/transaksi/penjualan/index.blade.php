@extends('layouts.template')

@section('content')
<div class="container-fluid">
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
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="total-items-sold">0</h3>
                    <p>Barang Terjual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cash-register mr-1"></i> Transaksi Penjualan
            </h3>
            <div class="card-tools">
                <a href="{{ url('/transaksi/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right" id="date-range">
                        <div class="input-group-append">
                            <button type="button" id="filter-date" class="btn btn-default">Filter</button>
                            <button type="button" id="reset-filter" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            <table id="transactions-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Kasir</th>
                        <th>Jumlah Barang</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- dynamically generated -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-transaction-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-white">
                    <i class="fas fa-info-circle mr-2"></i>Detail Transaksi
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detail-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail transaksi...</p>
                </div>

                <div id="detail-content" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Kode Transaksi</th>
                                    <td width="60%" id="detail-kode"></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td id="detail-tanggal"></td>
                                </tr>
                                <tr>
                                    <th>Pembeli</th>
                                    <td id="detail-pembeli"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Kasir</th>
                                    <td width="60%" id="detail-kasir"></td>
                                </tr>
                                <tr>
                                    <th>Total Barang</th>
                                    <td id="detail-total-barang"></td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td id="detail-total-harga"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="mb-3">Daftar Barang</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items"></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total:</th>
                                <th id="detail-grand-total"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="detail-error" class="text-center py-5" style="display: none;">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <p>Gagal memuat detail transaksi</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" id="btn-print-receipt" target="_blank">
                    <i class="fas fa-print mr-1"></i> Cetak Struk
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
<style>
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

    .transaction-link {
        cursor: pointer;
        color: #007bff;
    }

    .transaction-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@push('js')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(function() {
        $('#date-range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            startDate: moment().subtract(30, 'days'),
            endDate: moment()
        });

        var table = $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
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
            ],
            ajax: {
                url: "{{ url('/transaksi/list') }}",
                type: "POST",
                data: function(d) {
                    if ($('#date-range').data('daterangepicker')) {
                        d.start_date = $('#date-range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        d.end_date = $('#date-range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [{
                    data: 'penjualan_kode',
                    name: 'penjualan_kode',
                    render: function(data, type, row) {
                        return '<span class="transaction-link" data-id="' + row.penjualan_id + '">' + data + '</span>';
                    }
                },
                {
                    data: 'date',
                    name: 'penjualan_tanggal'
                },
                {
                    data: 'pembeli',
                    name: 'pembeli'
                },
                {
                    data: 'cashier',
                    name: 'cashier'
                },
                {
                    data: 'items',
                    name: 'items'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<div class="btn-group">' +
                            '<button type="button" class="btn btn-sm btn-info btn-detail" data-id="' + row.penjualan_id + '" data-toggle="tooltip" title="Detail">' +
                            '<i class="fas fa-eye"></i></button>' +
                            '<a href="{{ url('/transaksi') }}/' + row.penjualan_id + '/receipt" class="btn btn-sm btn-primary" target="_blank" data-toggle="tooltip" title="Cetak Struk">' +
                            '<i class="fas fa-print"></i></a>' +
                            '</div>';
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            initComplete: function() {
                updateTransactionStats();
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // Date filter button click
        $('#filter-date').on('click', function() {
            table.ajax.reload();
            updateTransactionStats();
        });

        // Reset filter button click
        $('#reset-filter').on('click', function() {
            $('#date-range').data('daterangepicker').setStartDate(moment().subtract(30, 'days'));
            $('#date-range').data('daterangepicker').setEndDate(moment());
            table.ajax.reload();
            updateTransactionStats();
        });

        // Transaction detail click handler (both for the code link and detail button)
        $('#transactions-table').on('click', '.transaction-link, .btn-detail', function() {
            var id = $(this).data('id');
            showTransactionDetail(id);
        });

        // Function to update the transaction statistics
        function updateTransactionStats() {
            var start = $('#date-range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var end = $('#date-range').data('daterangepicker').endDate.format('YYYY-MM-DD');

            $.ajax({
                url: '{{ url('/transaksi/stats') }}',
                type: 'GET',
                data: {
                    start_date: start,
                    end_date: end
                },
                success: function(response) {
                    $('#total-transactions').text(response.total_transactions);
                    $('#total-revenue').text('Rp ' + response.total_revenue);
                    $('#today-transactions').text(response.today_transactions);
                    $('#total-items-sold').text(response.total_items);
                }
            });
        }

        // Function to show transaction details in modal
        function showTransactionDetail(id) {
            $('#detail-loading').show();
            $('#detail-content').hide();
            $('#detail-error').hide();
            $('#btn-print-receipt').attr('href', '{{ url('/transaksi') }}/' + id + '/receipt');

            $('#modal-transaction-detail').modal('show');

            $.ajax({
                url: '{{ url('/transaksi') }}/' + id + '/detail',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#detail-loading').hide();

                    if (response.success) {
                        var transaction = response.transaction;
                        var detailHtml = '<div class="row">' +
                            '<div class="col-md-6">' +
                            '<p><strong>Kode Transaksi:</strong> ' + transaction.penjualan_kode + '</p>' +
                            '<p><strong>Tanggal:</strong> ' + transaction.formatted_date + '</p>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                            '<p><strong>Kasir:</strong> ' + transaction.user.nama + '</p>' +
                            '<p><strong>Total:</strong> Rp ' + transaction.formatted_total + '</p>' +
                            '</div>' +
                            '</div>' +
                            '<hr>' +
                            '<h5>Detail Produk:</h5>' +
                            '<table class="table table-sm table-bordered">' +
                            '<thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>' +
                            '<tbody>';

                        $.each(transaction.details, function(i, detail) {
                            detailHtml += '<tr>' +
                                '<td>' + detail.barang.barang_nama + '</td>' +
                                '<td>Rp ' + numberFormat(detail.harga) + '</td>' +
                                '<td>' + detail.jumlah + '</td>' +
                                '<td>Rp ' + numberFormat(detail.harga * detail.jumlah) + '</td>' +
                                '</tr>';
                        });

                        detailHtml += '</tbody></table>';

                        $('#detail-content').html(detailHtml);
                        $('#detail-content').show();
                    } else {
                        $('#detail-error').text(response.message).show();
                    }
                },
                error: function(xhr) {
                    $('#detail-loading').hide();
                    $('#detail-error').text('Error loading transaction details').show();
                }
            });
        }
        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    });
</script>
@endpush