@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Add New Supplier</h3>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-cancel">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="supplier_name" class="form-label">Supplier Name</label>
                            <input type="text" id="supplier_name" class="form-control" name="supplier_name" value="{{ old('supplier_name') }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="pic_supplier" class="form-label">Person In Charge (PIC)</label>
                            <input type="text" id="pic_supplier" class="form-control" name="pic_supplier" value="{{ old('pic_supplier') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="supplier_email" class="form-label">Email</label>
                            <input type="email" id="supplier_email" class="form-control" name="supplier_email" value="{{ old('supplier_email') }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="supplier_phone" class="form-label">Phone</label>
                            <input type="text" id="supplier_phone" class="form-control" name="supplier_phone" value="{{ old('supplier_phone') }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="supplier_address" class="form-label">Address</label>
                        <textarea id="supplier_address" name="supplier_address" class="form-control" rows="3">{{ old('supplier_address') }}</textarea>
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection