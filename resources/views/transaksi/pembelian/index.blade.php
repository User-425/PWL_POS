@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Transaksi Pembelian</h3>
            <div class="card-tools">
                <a href="{{ url('/pembelian/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Pembelian Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Mulai:</label>
                                        <div class="input-group date" id="start-date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#start-date" id="start_date">
                                            <div class="input-group-append" data-target="#start-date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Selesai:</label>
                                        <div class="input-group date" id="end-date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#end-date" id="end_date">
                                            <div class="input-group-append" data-target="#end-date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" id="filter-btn" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="pembelian-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pembelian</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Petugas</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detail-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Transaksi Pembelian</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Kode Pembelian</th>
                                    <td>:</td>
                                    <td><span id="pembelian-kode"></span></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>:</td>
                                    <td><span id="pembelian-tanggal"></span></td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>:</td>
                                    <td><span id="supplier-nama"></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Petugas</th>
                                    <td>:</td>
                                    <td><span id="user-nama"></span></td>
                                </tr>
                                <tr>
                                    <th>Total Items</th>
                                    <td>:</td>
                                    <td><span id="total-items"></span></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>:</td>
                                    <td><span id="grand-total"></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detail-items">
                                <!-- Detail items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <a href="#" id="print-receipt" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> Cetak Faktur
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Initialize date pickers
            $('#start-date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end-date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            // Set default dates (last 30 days)
            var today = moment();
            var thirtyDaysAgo = moment().subtract(30, 'days');
            $('#start_date').val(thirtyDaysAgo.format('YYYY-MM-DD'));
            $('#end_date').val(today.format('YYYY-MM-DD'));

            // Initialize DataTable
            var table = $('#pembelian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembelian.list') }}",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    { data: 'pembelian_kode', name: 'pembelian_kode' },
                    { data: 'date', name: 'date' },
                    { data: 'supplier.supplier_nama', name: 'supplier_name' },
                    { data: 'petugas', name: 'petugas' },
                    { data: 'items', name: 'items' },
                    { data: 'total', name: 'total' },
                    {
                        data: 'pembelian_id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%',
                        render: function (data, type, row) {
                            return `
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm detail-btn" data-id="${data}" style="margin-right: 5px;">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                            <a href="{{ url('pembelian/') }}/${data}/receipt" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    `;
                        }
                    }
                ],
                order: [[2, 'desc']],
                responsive: true,
                autoWidth: false,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Handle filter button click
            $('#filter-btn').click(function () {
                table.ajax.reload();
            });

            // Handle detail button click
            $(document).on('click', '.detail-btn', function () {
                var id = $(this).data('id');

                // Show loading state
                $('#detail-modal .modal-body').html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-2">Memuat data...</p></div>');
                $('#detail-modal').modal('show');

                // Load transaction details via AJAX
                $.ajax({
                    url: "{{ url('pembelian') }}/" + id + "/detail",
                    type: 'GET',
                    dataType: 'json', // Explicitly set dataType
                    success: function (response) {
                        // For debugging
                        console.log('Response received:', response);

                        if (response.success) {
                            var pembelian = response.pembelian;

                            // Clear previous modal content 
                            var modalBody = $('#detail-modal .modal-body');
                            modalBody.empty();

                            // Rebuild the entire content structure
                            var contentHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Kode Pembelian</th>
                                <td>:</td>
                                <td>${pembelian.pembelian_kode}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>:</td>
                                <td>${pembelian.formatted_date || pembelian.tanggal}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>:</td>
                                <td>${pembelian.supplier ? pembelian.supplier.supplier_nama : '-'}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Petugas</th>
                                <td>:</td>
                                <td>${pembelian.user.nama || '-'}</td>
                            </tr>
                            <tr>
                                <th>Total Items</th>
                                <td>:</td>
                                <td id="total-items"></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>:</td>
                                <td>Rp${pembelian.formatted_total}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items">
                            <!-- Detail items will be loaded here -->
                        </tbody>
                    </table>
                </div>`;

                            modalBody.html(contentHtml);

                            // Now populate the details table
                            var totalItems = 0;
                            var detailsHtml = '';

                            if (pembelian.details && pembelian.details.length > 0) {
                                $.each(pembelian.details, function (i, item) {
                                    // Use optional chaining to safely access nested properties
                                    totalItems += parseInt(item.jumlah || 0);
                                    const barangKode = item.barang ? item.barang.barang_kode : '-';
                                    const barangNama = item.barang ? item.barang.barang_nama : '-';
                                    const harga = item.harga || 0;
                                    const jumlah = item.jumlah || 0;

                                    detailsHtml += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${barangKode}</td>
                            <td>${barangNama}</td>
                            <td>Rp${numberFormat(harga)}</td>
                            <td>${jumlah}</td>
                            <td>Rp${numberFormat(harga * jumlah)}</td>
                        </tr>
                        `;
                                });
                            } else {
                                detailsHtml = '<tr><td colspan="6" class="text-center">Tidak ada data detail</td></tr>';
                            }

                            $('#total-items').text(totalItems);
                            $('#detail-items').html(detailsHtml);

                            // Set print receipt URL
                            $('#print-receipt').attr('href', "{{ url('pembelian') }}/" + pembelian.pembelian_id + "/receipt");

                        } else {
                            $('#detail-modal .modal-body').html('<div class="alert alert-danger">Gagal memuat data: ' + (response.message || 'Unknown error') + '</div>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        $('#detail-modal .modal-body').html(`
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
                    Terjadi kesalahan saat memuat data.<br>
                    Status: ${status}<br>
                    Error: ${error}
                </div>
            `);
                    }
                });
            });

            // Format number helper
            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
@endpush