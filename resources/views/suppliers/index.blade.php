@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
        <div class="main-content-card">
            <div class="table-controls">

                <a href="{{ route('suppliers.create') }}" class="btn add-btn">
                    <i class="fa-solid fa-plus"></i>
                    Add New Supplier
                </a>

                 <div class="search-bar-new">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="searchInput" name="search" placeholder="Search suppliers..." class="form-control" value="{{ request('search') }}">
                    <span class="clear-search-btn" id="clearSearchBtn" style="{{ request('search') ? 'display:block;' : 'display:none;' }}">&times;</span>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>PIC</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td><strong>{{ $supplier->supplier_name }}</strong></td>
                                <td>{{ $supplier->pic_supplier ?? '-' }}</td>
                                <td>{{ $supplier->supplier_email ?? '-' }}</td>
                                <td>{{ $supplier->supplier_phone ?? '-' }}</td>
                                <td>{{ Str::limit($supplier->supplier_address, 30, '...') }}</td>
                                <td class="text-center">
                                    <div class="action-icons">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" title="Show Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" title="Edit Supplier">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form class="d-inline" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Delete Supplier" data-name="{{ $supplier->supplier_name }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-secondary mt-3">
                                        No Supplier Data Available.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{-- Menambahkan appends agar parameter search tidak hilang saat paginasi --}}
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
        </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert for success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'SUCCESS',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // SweetAlert for delete confirmation
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                const dataName = this.getAttribute('data-name');
                const form = this.closest('form');

                Swal.fire({
                    title: `Are you sure you want to delete ${dataName}?`,
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#B80000',
                    cancelButtonColor: '#a4a4a4ff',
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
                // Hapus baris yang error terkait dateFilter
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