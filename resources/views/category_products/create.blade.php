@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Add New Category</h3>
                    <a href="{{ route('category_products.index') }}" class="btn btn-cancel">
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

                <form action="{{ route('category_products.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="product_category_name" class="form-label">Category Name</label>
                        <input type="text" id="product_category_name" class="form-control" name="product_category_name" value="{{ old('product_category_name') }}" placeholder="" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('category_products.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-save">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection