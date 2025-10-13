<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Edit Transaction #{{ $transaction->id }}</h3>
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">CASHIER NAME</label>
                                <input type="text" class="form-control" name="cashier_name" value="{{ old('cashier_name', $transaction->cashier_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">CUSTOMER EMAIL (Optional)</label>
                                <input type="email" class="form-control" name="customer_email" value="{{ old('customer_email', $transaction->customer_email) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">CREATED AT</label>
                                {{-- Ambil data created_at dari database dan format --}}
                                <input type="text" class="form-control" value="{{ $transaction->created_at->format('d F Y - H:i:s') }}" readonly>
                            </div>
                        </div>
                        <hr>
                        <h5>Select Products</h5>

                        {{-- Header untuk baris produk --}}
                        <div class="row fw-bold border-bottom pb-2 mb-2">
                            <div class="col-md-4">Product</div>
                            <div class="col-md-2">Unit Price</div>
                            <div class="col-md-2">Quantity</div>
                            <div class="col-md-3">Subtotal</div>
                            <div class="col-md-1"></div>
                        </div>

                        <div id="product-rows">
                            @foreach($transaction->details as $index => $detail)
                            @php
                                $productPrice = $detail->product ? $detail->product->price : 0;
                            @endphp
                            <div class="row mb-2 align-items-center product-row" data-price="{{ $productPrice }}">
                                <div class="col-md-4">
                                    <select name="products[{{ $index }}][id]" class="form-select product-select">
                                        <option value="">Select a Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                                {{ $product->title }} (Stock: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <span class="unit-price-text">Rp {{ number_format($productPrice, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input" min="1" placeholder="Qty" value="{{ $detail->quantity }}">
                                </div>
                                <div class="col-md-3">
                                    <span class="subtotal-text fw-bold">Rp 0</span>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="add-product-btn">Add Product</button>
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <h4>Grand Total: <span id="grand-total">Rp 0</span></h4>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">UPDATE</button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-warning">CANCEL</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    const products = @json($products->keyBy('id'));

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

    addProductBtn.addEventListener('click', function() {
        const index = new Date().getTime();
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2', 'align-items-center', 'product-row');
        newRow.dataset.price = "0";
        newRow.innerHTML = `
            <div class="col-md-4"><select name="products[${index}][id]" class="form-select product-select"><option value="">Select a Product</option>@foreach ($products as $product)<option value="{{ $product->id }}">{{ $product->title }} (Stock: {{ $product->stock }})</option>@endforeach</select></div>
            <div class="col-md-2"><span class="unit-price-text">Rp 0</span></div>
            <div class="col-md-2"><input type="number" name="products[${index}][quantity]" class="form-control quantity-input" min="1" placeholder="Qty"></div>
            <div class="col-md-3"><span class="subtotal-text fw-bold">Rp 0</span></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm btn-remove">X</button></div>`;
        productRowsContainer.appendChild(newRow);
    });
    
    productRowsContainer.addEventListener('change', function(e) {
        const row = e.target.closest('.product-row');
        if (!row) return;
        
        if (e.target.classList.contains('product-select')) {
            const selectedProductId = e.target.value;
            const unitPriceElement = row.querySelector('.unit-price-text');
            if (selectedProductId && products[selectedProductId]) {
                const price = products[selectedProductId].price;
                row.dataset.price = price;
                unitPriceElement.textContent = `Rp ${price.toLocaleString('id-ID')}`;
            } else {
                row.dataset.price = 0;
                unitPriceElement.textContent = `Rp 0`;
            }
        }
        calculateGrandTotal();
    });

    productRowsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.product-row').remove();
            calculateGrandTotal();
        }
    });

    // Kalkulasi total awal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', calculateGrandTotal);
</script>
</body>
</html>