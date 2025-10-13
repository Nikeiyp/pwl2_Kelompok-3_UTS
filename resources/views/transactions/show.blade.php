<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Detail</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Custom Form CSS (dipakai ulang dari create.blade.php) --}}
    <link rel="stylesheet" href="{{ asset('css/transaction-form.css') }}">
    {{-- General CSS (untuk background body, dll) --}}
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UTS Project</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Supplier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category_products.index') }}">Category Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('transactions.index') }}">Transactions</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">
                    @php
                        $invoiceId = 'INV-' . strtoupper($transaction->cashier_name) . '-' . $transaction->id;
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Transaction Detail</h3>
                        <h4 class="text-muted">{{ $invoiceId }}</h4>
                    </div>

                    {{-- Informasi Umum --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cashier Name</label>
                            <p class="fs-5"><strong>{{ $transaction->cashier_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Email</label>
                            <p class="fs-5"><strong>{{ $transaction->customer_email ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transaction Time</label>
                            <p class="fs-5"><strong>{{ $transaction->created_at->format('d F Y - H:i:s') }}</strong></p>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Bagian Produk --}}
                    <h5>Items Purchased</h5>

                    <table class="table table-borderless mt-3">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="py-3">Product</th>
                                <th class="py-3 text-center">Quantity</th>
                                <th class="py-3 text-end">Unit Price</th>
                                <th class="py-3 text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->title }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f8f9fa;">
                                <td colspan="3" class="text-end py-3">
                                    <h5 class="mb-0">Grand Total:</h5>
                                </td>
                                <td class="text-end py-3">
                                    <h5 class="mb-0"><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <hr class="my-4">
                    
                    <div class="form-actions justify-content-start">
                        <a href="{{ route('transactions.index') }}" class="btn btn-cancel">
                            <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>