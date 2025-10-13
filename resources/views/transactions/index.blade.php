<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Management</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}"> {{-- Pastikan ini mengarah ke file CSS yang benar --}}
    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UTS Project</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
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
                        <a class="nav-link active" aria-current="page" href="{{ route('transactions.index') }}">Transactions</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        {{-- Card utama yang membungkus semua konten --}}
        <div class="main-content-card">

            {{-- Header Atas: Judul dan Search Bar --}}
            <div class="top-header">
                <h3>Transaction Management</h3>
                <div class="search-bar-new">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="searchInput" name="search" placeholder="Search transactions..." class="form-control" value="{{ request('search') }}">
                    <span class="clear-search-btn" id="clearSearchBtn" style="{{ request('search') ? 'display:block;' : 'display:none;' }}">&times;</span>
                </div>
            </div>

            {{-- Kontrol Tabel: Tombol Add dan Filter --}}
            <div class="table-controls">
                <a href="{{ route('transactions.create') }}" class="btn add-btn">
                    <i class="fa-solid fa-plus"></i>
                    Add New Transaction
                </a>
                {{-- Filter Dropdown untuk Date/Month dipindahkan ke sini --}}
                <div class="filter-dropdowns">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dateDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- Tampilkan teks filter yang sedang aktif --}}
                            {{ request('date_filter') == 'today' ? 'Today' : (request('date_filter') == 'last_7_days' ? 'Last 7 Days' : (request('date_filter') == 'last_month' ? 'Last Month' : (request('date_filter') == 'all_time' ? 'All Time' : 'This Month'))) }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dateDropdownButton">
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'this_month'])) }}">This Month</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'today'])) }}">Today</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'last_7_days'])) }}">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'last_month'])) }}">Last Month</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', request()->except('date_filter', 'page')) }}">All Time</a></li> {{-- Menghapus filter tanggal --}}
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Main Transaction Table --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cashier Name</th>
                            <th>Customer Email</th>
                            <th>Date</th>
                            <th>Grand Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td><strong>#{{ $transaction->id }}</strong></td>
                                <td>{{ $transaction->cashier_name }}</td>
                                <td>{{ $transaction->customer_email ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d F, Y') }}</td>
                                <td><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
                                <td class="text-center">
                                    <div class="action-icons">
                                        <a href="{{ route('transactions.show', $transaction->id) }}" title="Show Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction->id) }}" title="Edit Transaction">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form class="d-inline" action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            @php
                                                $invoiceId = 'INV-' . strtoupper($transaction->cashier_name) . '-' . $transaction->id;
                                            @endphp
                                            <button type="submit" class="btn-delete" title="Delete Transaction" data-name="{{ $invoiceId }}">
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
                                        No Transaction Data Available.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

        // Auto-submit search when typing
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.addEventListener('keyup', function(event) {
            // Tampilkan/sembunyikan tombol X
            clearSearchBtn.style.display = this.value.length > 0 ? 'block' : 'none';

            if (event.key === 'Enter' || this.value.length === 0) { // Auto-submit jika Enter atau input kosong
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('search', this.value);
                // Pertahankan filter tanggal yang ada, kecuali parameter 'page'
                // Pastikan filter tanggal tetap aktif saat search
                const dateFilter = document.getElementById('dateDropdownButton').getAttribute('data-filter') || 'this_month';
                currentUrl.searchParams.set('date_filter', dateFilter);
                currentUrl.searchParams.delete('page'); // Reset halaman pagination saat mencari
                window.location.href = currentUrl.toString();
            }
        });
        
        // Fungsi saat tombol 'X' diklik untuk clear search
        clearSearchBtn.addEventListener('click', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('search'); // Hapus parameter 'search'
            currentUrl.searchParams.delete('page');   // Reset halaman pagination
            window.location.href = currentUrl.toString();
        });


        // Set active text for date filter dropdown and store current filter
        document.addEventListener('DOMContentLoaded', function() {
            const dateFilterParam = new URLSearchParams(window.location.search).get('date_filter');
            const dateDropdownButton = document.getElementById('dateDropdownButton');
            if (dateDropdownButton) {
                let text = 'This Month'; // Default jika tidak ada parameter
                if (dateFilterParam === 'today') text = 'Today';
                else if (dateFilterParam === 'last_7_days') text = 'Last 7 Days';
                else if (dateFilterParam === 'last_month') text = 'Last Month';
                else if (!dateFilterParam || dateFilterParam === 'all_time') text = 'All Time'; // Jika tidak ada atau explicitly 'all_time'
                dateDropdownButton.innerHTML = text;
                // Simpan filter aktif di data-filter attribute
                dateDropdownButton.setAttribute('data-filter', dateFilterParam || 'this_month'); // Default 'this_month'
            }

            // Inisialisasi tampilan tombol clear search saat halaman dimuat
            if (searchInput.value.length > 0) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
        });
    </script>
</body>
</html>