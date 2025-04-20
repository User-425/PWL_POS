@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">New Transaction</h3>
    </div>
    <div class="card-body">
        <form id="transaction-form">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pembeli">Customer Name</label>
                        <input type="text" class="form-control" id="pembeli" name="pembeli" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Transaction Date</label>
                        <input type="text" class="form-control" value="{{ date('Y-m-d H:i') }}" disabled>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h3 class="card-title">Add Products</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product-select">Select Product</label>
                                        <select id="product-select" class="form-control select2">
                                            <option value="">-- Select Product --</option>
                                            @foreach($availableProducts as $product)
                                            <option value="{{ $product->barang_id }}"
                                                data-name="{{ $product->barang_nama }}"
                                                data-price="{{ $product->harga_jual }}"
                                                data-stock="{{ $stockData[$product->barang_id] ?? 0 }}"
                                                data-code="{{ $product->barang_kode }}">
                                                {{ $product->barang_nama }} ({{ $product->barang_kode }}) - Stock: {{ $stockData[$product->barang_id] ?? 0 }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="product-qty">Quantity</label>
                                        <input type="number" min="1" value="1" class="form-control" id="product-qty">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="product-price">Price</label>
                                        <input type="text" class="form-control" id="product-price" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" id="add-product" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus"></i> Add
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
                            <h3 class="card-title text-white">Cart Items</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover" id="cart-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Product Code</th>
                                        <th width="35%">Product Name</th>
                                        <th width="15%">Price</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="empty-cart">
                                        <td colspan="7" class="text-center py-4">No products added yet</td>
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
                    <a href="{{ url('transaksi.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success" id="submit-transaction">
                        <i class="fas fa-save"></i> Complete Transaction
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
                <h4 class="modal-title">Transaction Completed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    <h3 class="mt-3">Transaction successful!</h3>
                    <p>Transaction has been saved successfully.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <a href="" id="view-transaction" class="btn btn-info">View Transaction</a>
                <a href="{{ url('transaksi.create') }}" class="btn btn-primary">New Transaction</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }

    .product-row {
        transition: all 0.3s ease;
    }

    .product-row.highlight {
        background-color: #ffffcc !important;
    }
    
    /* Custom positioning for toast container */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    /* Make sure toasts are visible above other elements */
    .toast {
        opacity: 1 !important;
    }
</style>
@endpush

@push('js')
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('body'),
            dropdownAutoWidth: true
        });

        // Re-initialize after short delay to fix potential timing issues
        setTimeout(function() {
            $('.select2').select2('destroy').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('body')
            });
        }, 100);

        // Set up toastr options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // This controls position
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        // Create a custom toast container if needed
        $('body').append('<div id="custom-toast-container"></div>');
        
        // Track cart items
        let cartItems = [];
        let totalAmount = 0;

        // Product selection handling
        $('#product-select').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                const price = parseFloat(selectedOption.data('price')) || 0;
                $('#product-price').val(formatCurrency(price));

                // Set max quantity based on stock
                const maxStock = parseInt(selectedOption.data('stock')) || 0;
                $('#product-qty').attr('max', maxStock);
                $('#product-qty').val(1); // Reset quantity
            } else {
                $('#product-price').val('');
                $('#product-qty').removeAttr('max');
            }
        });

        // Add product to cart
        $('#add-product').on('click', function() {
            console.log('Add product button clicked'); // Debugging

            const productSelect = $('#product-select');
            const selectedOption = productSelect.find('option:selected');

            // Check if a product is selected
            if (!selectedOption.val()) {
                showToast('Please select a product', 'warning');
                return;
            }

            const productId = selectedOption.val();
            const productCode = selectedOption.data('code') || '';
            const productName = selectedOption.data('name') || '';
            const price = parseFloat(selectedOption.data('price')) || 0;
            const maxStock = parseInt(selectedOption.data('stock')) || 0;
            const qtyInput = $('#product-qty');
            const qty = parseInt(qtyInput.val()) || 0;

            // Validation
            if (isNaN(qty) || qty <= 0) {
                showToast('Please enter a valid quantity', 'warning');
                return;
            }

            if (qty > maxStock) {
                showToast(`Only ${maxStock} items available in stock`, 'warning');
                return;
            }

            // Check if product already in cart
            let existingIndex = cartItems.findIndex(item => item.barang_id === productId);
            if (existingIndex !== -1) {
                let existingItem = cartItems[existingIndex];
                if (existingItem.quantity + qty > maxStock) {
                    showToast(`Cannot add more than ${maxStock} items`, 'warning');
                    return;
                }
                existingItem.quantity += qty;
                existingItem.subtotal = existingItem.quantity * price;

                // Update the table row
                $(`#product-row-${existingIndex} td:eq(4)`).text(existingItem.quantity);
                $(`#product-row-${existingIndex} td:eq(5)`).text(formatCurrency(existingItem.subtotal));

                // Highlight the updated row
                $(`#product-row-${existingIndex}`).addClass('highlight');
                setTimeout(() => {
                    $(`#product-row-${existingIndex}`).removeClass('highlight');
                }, 1000);
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
            }

            // Update total
            updateTotal();

            // Reset form
            productSelect.val('').trigger('change');
            qtyInput.val(1);
            $('#product-price').val('');

            showToast('Product added to cart', 'success');
        });

        // Remove item from cart
        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            cartItems.splice(index, 1);
            rebuildCartTable();
            showToast('Product removed from cart', 'info');
        });

        // Handle form submission
        $('#transaction-form').on('submit', function(e) {
            e.preventDefault();

            if (cartItems.length === 0) {
                showToast('Please add at least one product to the cart', 'warning');
                return;
            }

            if (!$('#pembeli').val().trim()) {
                showToast('Please enter the customer name', 'warning');
                return;
            }

            // Disable submit button and show loading
            $('#submit-transaction').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            // Prepare data for submission
            const formData = {
                _token: $('input[name="_token"]').val(),
                pembeli: $('#pembeli').val(),
                products: cartItems.map(item => ({
                    barang_id: item.barang_id,
                    quantity: item.quantity,
                    price: item.price
                }))
            };

            // Submit form via AJAX
            $.ajax({
                url: "{{ route('transaksi.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#view-transaction').attr('href', "{{ url('transaksi') }}/" + response.transaction_id);
                        $('#transaction-success-modal').modal('show');
                    } else {
                        showToast(response.message || 'An error occurred', 'error');
                    }
                    $('#submit-transaction').prop('disabled', false).html('<i class="fas fa-save"></i> Complete Transaction');
                },
                error: function(xhr) {
                    let errorMessage = 'Transaction failed';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast(errorMessage, 'error');
                    $('#submit-transaction').prop('disabled', false).html('<i class="fas fa-save"></i> Complete Transaction');
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
                tbody.append('<tr id="empty-cart"><td colspan="7" class="text-center py-4">No products added yet</td></tr>');
            } else {
                cartItems.forEach((item, index) => {
                    tbody.append(`
                    <tr id="product-row-${index}">
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
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
        }

        function showToast(message, type = 'info') {
            // Check if toastr is defined before using it
            if (typeof toastr !== 'undefined') {
                // toastr[type](message);
                
                // If toastr still doesn't appear correctly, try this alternative:
                $('#custom-toast-container').html(`
                   <div class="alert alert-${type} alert-dismissible fade show">
                       <button type="button" class="close" data-dismiss="alert">&times;</button>
                       ${message}
                   </div>
                `);
            } else {
                console.log(`[${type.toUpperCase()}] ${message}`);
                alert(message);
            }
        }
    });
</script>
@endpush