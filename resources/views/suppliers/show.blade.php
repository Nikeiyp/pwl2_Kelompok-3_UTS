@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Supplier Detail</h3>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-cancel">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
                
                <h2 class="mb-4">{{ $supplier->supplier_name }}</h2>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Person In Charge (PIC)</label>
                        <p class="fs-5"><strong>{{ $supplier->pic_supplier ?? '-' }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <p class="fs-5"><strong>{{ $supplier->supplier_email ?? '-' }}</strong></p>
                    </div>
                </div>
                 <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <p class="fs-5"><strong>{{ $supplier->supplier_phone ?? '-' }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <p class="fs-5"><strong>{{ $supplier->supplier_address ?? '-' }}</strong></p>
                    </div>
                </div>
                 <hr>
                 <div>
                     <label class="form-label">Registered On</label>
                     <p class="fs-5"><strong>{{ $supplier->created_at ? $supplier->created_at->format('d F Y') : '-' }}</strong></p>
                 </div>
            </div>
        </div>
    </div>
@endsection