<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: lightgray">

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

    <div class="container mt-5">
        <div class="main-content-card">
            <div class="top-header">
                <h3>Product Management</h3>
                <div class="search-bar-new">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="searchInput" name="search" placeholder="Search products..." class="form-control" value="{{ request('search') }}">
                    <span class="clear-search-btn" id="clearSearchBtn" style="{{ request('search') ? 'display:block;' : 'display:none;' }}">&times;</span>
                </div>
            </div>

            <div class="table-controls">
                <a href="{{ route('products.create') }}" class="btn add-btn">
                    <i class="fa-solid fa-plus"></i>
                    Add New Product
                </a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('/storage/images/'.$product->image) }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td><strong>{{ $product->title }}</strong></td>
                                <td>{{ $product->product_category_name ?? '-' }}</td>
                                <td>{{ $product->supplier_name ?? '-' }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td class="text-center">
                                    <div class="action-icons">
                                        <a href="{{ route('products.show', $product->id) }}" title="Show Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" title="Edit Product">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form class="d-inline" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Delete Product" data-name="{{ $product->title }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="alert alert-secondary mt-3">
                                        No Product Data Available.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{-- Tambahkan appends(request()->query()) agar search term tidak hilang saat pindah halaman --}}
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert untuk pesan sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'SUCCESS',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // SweetAlert untuk konfirmasi hapus
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const dataName = this.getAttribute('data-name');
                const form = this.closest('form');

                Swal.fire({
                    title: `Delete product "${dataName}"?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.addEventListener('keyup', function(event) {
            clearSearchBtn.style.display = this.value.length > 0 ? 'block' : 'none';
            if (event.key === 'Enter') {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('search', this.value);
                currentUrl.searchParams.delete('page');
                window.location.href = currentUrl.toString();
            }
        });
        
        clearSearchBtn.addEventListener('click', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        });
        
        // Event listener saat tombol 'X' diklik
        clearSearchBtn.addEventListener('click', function() {
            const currentUrl = new URL(window.location.href);
            // Hapus parameter 'search' dan 'page' dari URL
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.delete('page');
            // Arahkan browser ke URL yang sudah bersih (halaman awal)
            window.location.href = currentUrl.toString();
        });
    </script>
</body>
</html>