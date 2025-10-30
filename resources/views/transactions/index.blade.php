@extends('layouts.app')

@section('title', 'Transaction Management')

@section('content')

    <div class="main-content-card">
        <div class="table-controls">
            <a href="{{ route('transactions.create') }}" class="btn add-btn">
                <i class="fa-solid fa-plus"></i>
                Add New Transaction
            </a>
            <div class="header-actions">
                <div class="search-bar-new">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="searchInput" name="search" placeholder="Search transactions..." class="form-control" value="{{ request('search') }}">
                    <span class="clear-search-btn" id="clearSearchBtn" style="{{ request('search') ? 'display:block;' : 'display:none;' }}">&times;</span>
                </div>
                <div class="filter-dropdowns">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dateDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- This logic correctly displays the active date filter --}}
                            @php
                                $dateFilter = request('date_filter');
                                if ($dateFilter == 'today') echo 'Today';
                                elseif ($dateFilter == 'last_7_days') echo 'Last 7 Days';
                                elseif ($dateFilter == 'last_month') echo 'Last Month';
                                elseif ($dateFilter == 'all_time') echo 'All Time';
                                else echo 'This Month';
                            @endphp
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dateDropdownButton">
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'this_month'])) }}">This Month</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'today'])) }}">Today</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'last_7_days'])) }}">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'last_month'])) }}">Last Month</a></li>
                            <li><a class="dropdown-item" href="{{ route('transactions.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'all_time'])) }}">All Time</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cashier Name</th>
                        <th>Customer Email</th>
                        <th>Date</th>
                        <th>Grand Total</th>
                        <th class="text-center">Actions</th>
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
                                    <a href="{{ route('transactions.show', $transaction->id) }}" title="Show Details"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('transactions.edit', $transaction->id) }}" title="Edit Transaction"><i class="fa-solid fa-pencil"></i></a>
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
                                <div class="alert alert-secondary mt-3">No Transaction Data Available.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

        // Search script
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