<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/transaction-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                         <h3>Add New Product</h3>
                         <a href="{{ route('products.index') }}" class="btn btn-cancel">
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

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="form-label">Product Name</label>
                            <input type="text" id="title" class="form-control" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="product_category_id" class="form-label">Category</label>
                                <select id="product_category_id" name="product_category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach ($data['categories'] as $category)
                                        <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->product_category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="supplier_id" class="form-label">Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="form-select" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($data['suppliers'] as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" id="image" name="image" class="form-control" required>
                        </div>
                        
                        <div class="form-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-cancel">Cancel</a>
                            <button type="submit" class="btn btn-save">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>