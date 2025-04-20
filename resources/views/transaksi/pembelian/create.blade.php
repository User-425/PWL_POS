@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Transaksi Pembelian Baru</h3>
    </div>
    <div class="card-body">
        <form id="pembelian-form">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group" id="supplier-select-group">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-control choices" id="supplier_id" name="supplier_id" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}">
                                {{ $supplier->supplier_nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="text" class="form-control" value="{{ date('Y-m-d H:i') }}" disabled>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Barang</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group" id="product-select-group">
                                        <label for="product-select">Pilih Barang</label>
                                        <select id="product-select" class="form-control choices">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->barang_id }}"
                                                data-name="{{ $product->barang_nama }}"
                                                data-price="{{ $product->harga_beli }}"
                                                data-stock="{{ $stockData[$product->barang_id] ?? 0 }}"
                                                data-code="{{ $product->barang_kode }}">
                                                {{ $product->barang_nama }} ({{ $product->barang_kode }}) - Stok: {{ $stockData[$product->barang_id] ?? 0 }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="product-price">Harga Beli</label>
                                        <input type="number" min="1" class="form-control" id="product-price">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="product-qty">Jumlah</label>
                                        <input type="number" min="1" value="1" class="form-control" id="product-qty">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" id="add-product" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Daftar Barang</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover" id="cart-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Kode Barang</th>
                                        <th width="35%">Nama Barang</th>
                                        <th width="15%">Harga Beli</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="empty-cart">
                                        <td colspan="7" class="text-center py-4">Belum ada barang ditambahkan</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Total</th>
                                        <th colspan="2" id="total-amount">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ url('pembelian') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success" id="submit-transaction">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="transaction-success-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Transaksi Berhasil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    <h3 class="mt-3">Transaksi Berhasil!</h3>
                    <p>Transaksi pembelian telah berhasil disimpan.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <a href="" id="view-transaction" class="btn btn-info">Lihat Transaksi</a>
                <a href="{{ url('pembelian/create') }}" class="btn btn-primary">Transaksi Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
<style>
    .product-row {
        transition: all 0.3s ease;
    }

    .product-row.highlight {
        background-color: #ffffcc !important;
    }
    
    /* Native select styling */
    select.form-control {
        appearance: auto;
        padding-right: 30px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6' viewBox='0 0 8 6'%3E%3Cpath fill='%23666' d='M0 0h8L4 6z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 8px 6px;
    }
    
    /* Improve focus state styling */
    select.form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush

@push('js')
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initially disable product select until supplier is chosen
        $('#product-select').prop('disabled', true);
        
        // Track cart items
        let cartItems = [];
        let totalAmount = 0;

        // Listen to supplier change to load products
        $('#supplier_id').on('change', function() {
            const supplierId = $(this).val();
            const productSelect = $('#product-select');
            
            // Clear previous options and price
            productSelect.empty().append('<option value="">-- Pilih Barang --</option>');
            $('#product-price').val('');
            
            if (supplierId) {
                // Disable product select while loading
                productSelect.prop('disabled', true).append('<option value="" disabled>Memuat produk...</option>');
                
                // Fetch products for the selected supplier
                $.ajax({
                    url: "{{ route('api.products.by.supplier') }}",
                    type: "GET",
                    data: { supplier_id: supplierId },
                    dataType: 'json',
                    success: function(response) {
                        // Clear loading option
                        productSelect.empty().append('<option value="">-- Pilih Barang --</option>');
                        
                        if (response.products && response.products.length > 0) {
                            // Add products to dropdown
                            response.products.forEach(product => {
                                const stockAmount = product.stock || 0;
                                productSelect.append(
                                    $('<option>', {
                                        value: product.barang_id,
                                        text: `${product.barang_nama} (${product.barang_kode}) - Stok: ${stockAmount}`,
                                        'data-code': product.barang_kode,
                                        'data-name': product.barang_nama,
                                        'data-price': product.harga_beli,
                                        'data-stock': stockAmount
                                    })
                                );
                            });
                        } else {
                            showToast('info', 'Tidak Ada Produk', 'Tidak ada produk yang tersedia untuk supplier ini');
                        }
                        
                        // Enable product select
                        productSelect.prop('disabled', false);
                    },
                    error: function() {
                        // Clear loading option
                        productSelect.empty().append('<option value="">-- Pilih Barang --</option>');
                        productSelect.prop('disabled', false);
                        showToast('error', 'Gagal Memuat Data', 'Gagal memuat data produk');
                    }
                });
            } else {
                // If no supplier selected, disable product select
                productSelect.prop('disabled', true);
            }
        });

        // Product selection handling
        $('#product-select').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            
            if (selectedOption.val()) {
                const price = parseFloat(selectedOption.data('price')) || 0;
                $('#product-price').val(price);
                $('#product-qty').val(1);
            } else {
                $('#product-price').val('');
                $('#product-qty').val(1);
            }
        });

        // Add product to cart
        $('#add-product').on('click', function() {
            const selectedOption = $('#product-select option:selected');
            
            // Check if a product is selected
            if (!selectedOption.val()) {
                showToast('warning', 'Perhatian', 'Silakan pilih barang terlebih dahulu');
                return;
            }

            const productId = selectedOption.val();
            const productCode = selectedOption.data('code') || '';
            const productName = selectedOption.data('name') || '';
            const priceInput = $('#product-price');
            const price = parseFloat(priceInput.val()) || 0;
            const qtyInput = $('#product-qty');
            const qty = parseInt(qtyInput.val()) || 0;

            // Validation
            if (isNaN(price) || price <= 0) {
                showToast('warning', 'Validasi Harga', 'Harga beli harus valid dan lebih besar dari 0');
                priceInput.focus();
                return;
            }

            if (isNaN(qty) || qty <= 0) {
                showToast('warning', 'Validasi Jumlah', 'Jumlah harus valid dan lebih besar dari 0');
                qtyInput.focus();
                return;
            }

            // Check if product already in cart
            let existingIndex = cartItems.findIndex(item => item.barang_id === productId);
            if (existingIndex !== -1) {
                let existingItem = cartItems[existingIndex];
                existingItem.quantity += qty;
                existingItem.price = price; // Update price in case it changed
                existingItem.subtotal = existingItem.quantity * price;

                // Update the table row
                $(`#product-row-${existingIndex} td:eq(3)`).text(formatCurrency(existingItem.price));
                $(`#product-row-${existingIndex} td:eq(4)`).text(existingItem.quantity);
                $(`#product-row-${existingIndex} td:eq(5)`).text(formatCurrency(existingItem.subtotal));

                // Highlight the updated row
                $(`#product-row-${existingIndex}`).addClass('highlight');
                setTimeout(() => {
                    $(`#product-row-${existingIndex}`).removeClass('highlight');
                }, 1000);
                showToast('info', 'Jumlah Diperbarui', `Jumlah ${productName} diperbarui`);
            } else {
                // Add new item to cart
                const newItem = {
                    barang_id: productId,
                    code: productCode,
                    name: productName,
                    price: price,
                    quantity: qty,
                    subtotal: price * qty
                };

                cartItems.push(newItem);

                // Remove empty cart message if present
                $('#empty-cart').remove();

                // Add row to table
                const rowIndex = cartItems.length - 1;
                const newRow = `
                <tr id="product-row-${rowIndex}" class="product-row highlight">
                    <td>${rowIndex + 1}</td>
                    <td>${productCode}</td>
                    <td>${productName}</td>
                    <td>${formatCurrency(price)}</td>
                    <td>${qty}</td>
                    <td>${formatCurrency(price * qty)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${rowIndex}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;

                $('#cart-table tbody').append(newRow);

                setTimeout(() => {
                    $(`#product-row-${rowIndex}`).removeClass('highlight');
                }, 1000);
                showToast('success', 'Item Ditambahkan', 'Barang berhasil ditambahkan');
            }

            // Update total
            updateTotal();

            // Reset product selection
            $('#product-select').val(''); 
            $('#product-price').val('');
            $('#product-qty').val(1);
        });

        // Remove item from cart
        $(document).on('click', '.remove-item', function() {
            const indexToRemove = $(this).data('index');
            cartItems.splice(indexToRemove, 1);
            rebuildCartTable();
            showToast('info', 'Item Dihapus', 'Barang dihapus dari daftar');
        });

        // Handle form submission
        $('#pembelian-form').on('submit', function(e) {
            e.preventDefault();

            if (cartItems.length === 0) {
                showToast('warning', 'Keranjang Kosong', 'Tambahkan minimal satu barang ke daftar');
                return;
            }

            if (!$('#supplier_id').val()) {
                showToast('warning', 'Supplier Diperlukan', 'Pilih supplier terlebih dahulu');
                return;
            }

            // Disable submit button and show loading
            $('#submit-transaction').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            // Prepare data for submission
            const formData = {
                _token: $('input[name="_token"]').val(),
                supplier_id: $('#supplier_id').val(),
                products: cartItems.map(item => ({
                    barang_id: item.barang_id,
                    quantity: item.quantity,
                    price: item.price
                }))
            };

            // Submit form via AJAX
            $.ajax({
                url: "{{ route('pembelian.store') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.pembelian_id) {
                        // Reset form state
                        cartItems = [];
                        rebuildCartTable();
                        $('#supplier_id').val('');
                        $('#product-select').empty().append('<option value="">-- Pilih Barang --</option>').prop('disabled', true);
                        $('#product-price').val('');
                        $('#product-qty').val(1);

                        // Show success modal
                        $('#view-transaction').attr('href', "{{ url('pembelian') }}/" + response.pembelian_id);
                        $('#transaction-success-modal').modal('show');
                    } else {
                        showToast('error', 'Transaksi Gagal', response.message || 'Terjadi kesalahan saat menyimpan transaksi');
                    }
                    $('#submit-transaction').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Transaksi');
                },
                error: function(xhr) {
                    let errorMessage = 'Transaksi gagal. Periksa koneksi atau data input.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('error', 'Error', errorMessage);
                    $('#submit-transaction').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Transaksi');
                }
            });
        });

        // Helper functions
        function updateTotal() {
            totalAmount = cartItems.reduce((sum, item) => sum + (item.subtotal || 0), 0);
            $('#total-amount').text(formatCurrency(totalAmount));
        }

        function rebuildCartTable() {
            const tbody = $('#cart-table tbody');
            tbody.empty();

            if (cartItems.length === 0) {
                tbody.append('<tr id="empty-cart"><td colspan="7" class="text-center py-4">Belum ada barang ditambahkan</td></tr>');
            } else {
                cartItems.forEach((item, index) => {
                    // Recalculate subtotal just in case
                    item.subtotal = item.price * item.quantity;
                    // Append new row with updated index
                    tbody.append(`
                    <tr id="product-row-${index}" class="product-row">
                        <td>${index + 1}</td>
                        <td>${item.code}</td>
                        <td>${item.name}</td>
                        <td>${formatCurrency(item.price)}</td>
                        <td>${item.quantity}</td>
                        <td>${formatCurrency(item.subtotal)}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `);
                });
            }

            updateTotal();
        }

        function formatCurrency(amount) {
            // Ensure amount is a number, default to 0 if not
            const numericAmount = Number(amount);
            if (isNaN(numericAmount)) {
                return 'Rp 0';
            }
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(numericAmount);
        }
    });
</script>
@endpush