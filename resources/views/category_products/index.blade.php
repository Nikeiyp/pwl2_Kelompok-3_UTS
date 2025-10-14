@extends('layouts.app')

@section('title', 'Category Management')

@section('content')

    <div class="main-content-card">
        <div class="table-controls">
            <a href="{{ route('category_products.create') }}" class="btn add-btn">
                <i class="fa-solid fa-plus"></i>
                Add New Category
            </a>
            <div class="search-bar-new">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="searchInput" name="search" placeholder="Search categories..." class="form-control" value="{{ request('search') }}">
                <span class="clear-search-btn" id="clearSearchBtn" style="{{ request('search') ? 'display:block;' : 'display:none;' }}">&times;</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category_products as $category)
                        <tr>
                            <td><strong>#{{ $category->id }}</strong></td>
                            <td>{{ $category->product_category_name }}</td>
                            <td>{{ $category->created_at ? $category->created_at->format('d F Y') : '-' }}</td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <a href="{{ route('category_products.edit', $category->id) }}" title="Edit Category">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <form class="d-inline" action="{{ route('category_products.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Delete Category" data-name="{{ $category->product_category_name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="alert alert-secondary mt-3">
                                    No Category Data Available.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $category_products->appends(request()->query())->links() }}
        </div>
    </div>

@endsection

@push('scripts')
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
                    title: `Delete category "${dataName}"?`,
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

        // Script untuk Search Bar
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

        document.addEventListener('DOMContentLoaded', function() {
            if (searchInput.value && searchInput.value.length > 0) {
                clearSearchBtn.style.display = 'block';
            }
        });
    </script>
@endpush