<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- CSS yang sudah ada --}}
    <link rel="stylesheet" href="{{ asset('css/transaction-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UTS Project</a>
            <div class="collapse navbar-collapse">
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
                        <a class="nav-link" href="{{ route('transactions.index') }}">Transactions</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <h3>Add New Category</h3>
                    
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
                            <input type="text" id="product_category_name" class="form-control" name="product_category_name" value="{{ old('product_category_name') }}" placeholder="e.g., Smartphone" required>
                        </div>
                        
                        <div class="form-actions">
                            <a href="{{ route('category_products.index') }}" class="btn btn-cancel">Cancel</a>
                            <button type="submit" class="btn btn-save">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>