@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Product Detail</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-cancel">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('/storage/images/'.$product->image) }}" class="img-fluid rounded" alt="{{ $product->title }}">
                    </div>
                    <div class="col-md-8">
                        <h2 class="mb-3">{{ $product->title }}</h2>
                        <hr>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Category</label>
                                <p class="fs-5"><strong>{{ $product->product_category_name ?? '-' }}</strong></p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Supplier</label>
                                <p class="fs-5"><strong>{{ $product->supplier_name ?? '-' }}</strong></p>
                            </div>
                        </div>
                         <hr>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Price</label>
                                <p class="fs-5"><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Stock</label>
                                <p class="fs-5"><strong>{{ $product->stock }}</strong></p>
                            </div>
                        </div>
                         <hr>
                         <div>
                             <label class="form-label">Description</label>
                             <div class="p-3 bg-light rounded" style="border: 1px solid #e0e0e0;">
                                 <p>{!! $product->description !!}</p>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection