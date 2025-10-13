<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Penjualan Kue')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1A0701;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 1rem;
        }

        .sidebar .brand {
            font-size: 1.4rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 1rem;
        }

        .sidebar .nav-link {
            color: #f8f9fa;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        .sidebar .nav-link i {
            width: 22px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            border-radius: 5px;
        }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }

        .page-title {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .card {
            border: none;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

    {{-- SIDEBAR KIRI --}}
    <div class="sidebar">
        <div class="brand">Admin Musik</div>

        <a href="{{ route('dashboard') }}" 
           class="nav-link @if(request()->routeIs('dashboard')) active @endif">
           <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>

        <a href="{{ route('transactions.create') }}" 
           class="nav-link @if(request()->routeIs('transactions.create')) active @endif">
           <i class="fas fa-cash-register me-2"></i> Create Transaction
        </a>

        <a href="{{ route('transactions.index') }}" 
           class="nav-link @if(request()->routeIs('transactions.index')) active @endif">
           <i class="fas fa-history me-2"></i> Transaction History
        </a>

        <a href="{{ route('products.index') }}" 
           class="nav-link @if(request()->routeIs('products.index')) active @endif">
           <i class="fas fa-box me-2"></i> Product
        </a>

        <a href="{{ route('category_products.index') }}" 
           class="nav-link @if(request()->routeIs('category_products.index')) active @endif">
           <i class="fas fa-tags me-2"></i> Product Category
        </a>

        <a href="{{ route('suppliers.index') }}" 
           class="nav-link @if(request()->routeIs('suppliers.index')) active @endif">
           <i class="fas fa-truck me-2"></i> Supplier
        </a>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content">
        <h2 class="page-title">@yield('title')</h2>
        @yield('content')
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @stack('scripts')

</body>
</html>
