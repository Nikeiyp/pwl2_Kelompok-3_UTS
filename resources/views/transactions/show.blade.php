@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                @php
                    $invoiceId = 'INV-' . strtoupper($transaction->cashier_name) . '-' . $transaction->id;
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Transaction Detail</h3>
                     <a href="{{ route('transactions.index') }}" class="btn btn-cancel">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
                
                <h4 class="text-muted mb-4">{{ $invoiceId }}</h4>

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
            </div>
        </div>
    </div>
@endsection