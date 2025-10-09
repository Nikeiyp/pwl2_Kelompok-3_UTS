<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Transaction Detail #{{ $transaction->id }}</h3>
            </div>
            <div class="card-body">
                <p><strong>Cashier:</strong> {{ $transaction->cashier_name }}</p>
                <p><strong>Customer Email:</strong> {{ $transaction->customer_email ?? '-' }}</p>
                
                {{-- UBAH BARIS DI BAWAH INI --}}
                <p><strong>Date:</strong> {{ $transaction->created_at->format('d F Y - H:i:s') }}</p>
                {{-- BATAS PERUBAHAN --}}

                <hr>
                <h5>Items Purchased</h5>
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($transaction->details as $detail)
                            @php
                                $subtotal = $detail->product->price * $detail->quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $detail->product->title }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp {{ number_format($detail->product->price) }}</td>
                                <td>Rp {{ number_format($subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr><th colspan="3" class="text-end">Total:</th><th>Rp {{ number_format($total) }}</th></tr></tfoot>
                </table>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</body>
</html>