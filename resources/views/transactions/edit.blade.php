@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                @php
                    $invoiceId = 'INV-' . strtoupper($transaction->cashier_name) . '-' . $transaction->id;
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Edit Transaction</h3>
                     <a href="{{ route('transactions.index') }}" class="btn btn-cancel">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="cashier_name" class="form-label">Cashier Name</label>
                            <input type="text" id="cashier_name" class="form-control" name="cashier_name" value="{{ old('cashier_name', $transaction->cashier_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="customer_email" class="form-label">Customer Email (Optional)</label>
                            <input type="email" id="customer_email" class="form-control" name="customer_email" value="{{ old('customer_email', $transaction->customer_email) }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Transaction Time</label>
                            <input type="text" class="form-control" value="{{ $transaction->created_at->format('d F Y - H:i:s') }}" readonly>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5>Select Products</h5>
                    
                    <div id="product-rows-header" class="d-none d-md-grid">
                        <div>Product</div>
                        <div>Unit Price</div>
                        <div>Quantity</div>
                        <div>Subtotal</div>
                        <div></div>
                    </div>
                    
                    <div id="product-rows">
                        @foreach($transaction->details as $detail)
                            @php
                                $productPrice = $detail->product ? $detail->product->price : 0;
                                $availableStock = $detail->product ? $detail->product->stock + $detail->quantity : 0;
                            @endphp
                            <div class="product-row" data-price="{{ $productPrice }}" data-stock="{{ $availableStock }}">
                                <div>
                                    <select name="products[{{ $loop->index }}][id]" class="form-select product-select" required>
                                        <option value="">Select a Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                                {{ $product->title }} (Stock: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><span class="unit-price-text">Rp {{ number_format($productPrice, 0, ',', '.') }}</span></div>
                                <div><input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control quantity-input" min="1" max="{{ $availableStock }}" value="{{ $detail->quantity }}" required></div>
                                <div><span class="subtotal-text fw-bold">Rp 0</span></div>
                                <div class="text-end"><button type="button" class="btn btn-remove-product">&times;</button></div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary mt-2" id="add-product-btn">
                        <i class="fa-solid fa-plus me-1"></i> Add Product
                    </button>
                    
                    <hr class="my-4">
                    
                    <div class="form-actions">
                        <h4 class="me-auto" id="grand-total-display">Grand Total: <span id="grand-total">Rp 0</span></h4>
                        <a href="{{ route('transactions.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save">Update Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const productsData = @json($productsJson);
        const productRowsContainer = document.getElementById('product-rows');
        const addProductBtn = document.getElementById('add-product-btn');
        const grandTotalElement = document.getElementById('grand-total');

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const price = parseFloat(row.dataset.price) || 0;
                const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
                const subtotal = price * quantity;
                row.querySelector('.subtotal-text').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
                total += subtotal;
            });
            grandTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function addProductRow() {
            const index = new Date().getTime();
            const newRow = document.createElement('div');
            newRow.classList.add('product-row');
            newRow.dataset.price = "0";
            newRow.dataset.stock = "0"; 

            let productOptions = '<option value="">Select a Product</option>';
            @foreach ($products as $product)
                productOptions += `<option value="{{ $product->id }}">{{ $product->title }} (Stock: {{ $product->stock }})</option>`;
            @endforeach

            newRow.innerHTML = `
                <div><select name="products[${index}][id]" class="form-select product-select" required>${productOptions}</select></div>
                <div><span class="unit-price-text">Rp 0</span></div>
                <div><input type="number" name="products[${index}][quantity]" class="form-control quantity-input" min="1" placeholder="Qty" required></div>
                <div><span class="subtotal-text fw-bold">Rp 0</span></div>
                <div class="text-end"><button type="button" class="btn btn-remove-product">&times;</button></div>`;
            productRowsContainer.appendChild(newRow);
        }

        addProductBtn.addEventListener('click', addProductRow);

        productRowsContainer.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                const row = e.target.closest('.product-row');
                if (!row) return;
                const stock = parseInt(row.dataset.stock) || 0;
                const quantity = parseInt(e.target.value) || 0;
                if (stock > 0 && quantity > stock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Insufficient Stock',
                        text: `Quantity cannot exceed available stock (${stock})!`,
                        confirmButtonColor: '#0d6efd'
                    }).then(() => {
                        e.target.value = stock;
                        calculateGrandTotal();
                    });
                } else {
                    calculateGrandTotal();
                }
            }
        });

        productRowsContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const row = e.target.closest('.product-row');
                if (!row) return;
                const selectedProductId = e.target.value;
                const unitPriceElement = row.querySelector('.unit-price-text');
                const quantityInput = row.querySelector('.quantity-input');
                if (selectedProductId && productsData[selectedProductId]) {
                    const product = productsData[selectedProductId];
                    row.dataset.price = product.price;
                    row.dataset.stock = product.stock; 
                    unitPriceElement.textContent = `Rp ${product.price.toLocaleString('id-ID')}`;
                    quantityInput.value = 1;
                    quantityInput.max = product.stock;
                } else {
                    row.dataset.price = 0;
                    row.dataset.stock = 0;
                    unitPriceElement.textContent = `Rp 0`;
                    quantityInput.value = '';
                    quantityInput.max = '';
                }
                calculateGrandTotal();
            }
        });
        
        productRowsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-product')) {
                e.target.closest('.product-row').remove();
                calculateGrandTotal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            addProductRow();
        });
    </script>
@endpush