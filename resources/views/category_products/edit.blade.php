<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('category_products.update', $category_product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">NAMA KATEGORI</label>
                                <input type="text" class="form-control @error('product_category_name') is-invalid @enderror" name="product_category_name" value="{{ old('product_category_name', $category_product->product_category_name) }}" placeholder="Masukkan Nama Kategori">
                                @error('product_category_name')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-md btn-primary">UPDATE</button>
                             <a href="{{ route('category_products.index') }}" class="btn btn-md btn-secondary">KEMBALI</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>