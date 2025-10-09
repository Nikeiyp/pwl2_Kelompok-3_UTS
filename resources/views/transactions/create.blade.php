<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Create Transaction</h3>
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">CASHIER NAME</label>
                                <input type="text" class="form-control @error('cashier_name') is-invalid @enderror" name="cashier_name" value="{{ old('cashier_name') }}">
                                @error('cashier_name')<div class="alert alert-danger mt-2">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">CUSTOMER EMAIL (Optional)</label>
                                <input type="email" class="form-control" name="customer_email" value="{{ old('customer_email') }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">TRANSACTION TIME</label>
                                    <input type="text" class="form-control" value="{{ now()->format('d F Y - H:i:s') }}" readonly>
                                </div>
                            </div>  
                        </div>
                        <hr>
                        <h5>Select Products</h5>
                        <div class="row fw-bold border-bottom pb-2 mb-2">
                            <div class="col-md-4">Product</div>
                            <div class="col-md-2">Unit Price</div>
                            <div class="col-md-2">Quantity</div>
                            <div class="col-md-3">Subtotal</div>
                            <div class="col-md-1"></div>
                        </div>
                        
                        <div id="product-rows">
                            </div>

                        @error('products')<div class="alert alert-danger mt-2">You must select at least one product.</div>@enderror
                        <button type="button" class="btn btn-secondary mt-2" id="add-product-btn">Add Product</button>
                        <hr>
                        
                        <div class="d-flex justify-content-end">
                            <h4>Grand Total: <span id="grand-total">Rp 0</span></h4>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">SAVE</button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-warning">CANCEL</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    // Data produk dari model, diubah ke format JSON untuk JavaScript
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
        newRow.dataset.price = "0"; // Default price
        newRow.innerHTML = `
            <div class="col-md-4">
                <select name="products[${index}][id]" class="form-select product-select">
                    <option value="">Select a Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }} (Stock: {{ $product->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <span class="unit-price-text">Rp 0</span>
            </div>
            <div class="col-md-2">
                <input type="number" name="products[${index}][quantity]" class="form-control quantity-input" min="1" placeholder="Qty">
            </div>
            <div class="col-md-3">
                <span class="subtotal-text fw-bold">Rp 0</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
            </div>`;
        productRowsContainer.appendChild(newRow);
    });

    productRowsContainer.addEventListener('change', function(e) {
        const row = e.target.closest('.product-row');
        if (!row) return;

        // Jika dropdown produk berubah
        if (e.target.classList.contains('product-select')) {
            const selectedProductId = e.target.value;
            const unitPriceElement = row.querySelector('.unit-price-text');
            
            if (selectedProductId && products[selectedProductId]) {
                const price = products[selectedProductId].price;
                row.dataset.price = price; // Simpan harga di data-attribute
                unitPriceElement.textContent = `Rp ${price.toLocaleString('id-ID')}`; // Tampilkan harga dari model
            } else {
                row.dataset.price = 0;
                unitPriceElement.textContent = `Rp 0`;
            }
        }
        
        // Kalkulasi ulang total setiap kali ada perubahan
        calculateGrandTotal();
    });
    
    productRowsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.product-row').remove();
            calculateGrandTotal();
        }
    });

</script>
</body>
</html>