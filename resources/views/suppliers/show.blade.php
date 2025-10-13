<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

<div class="container mt-5 mb-5">
    <div class="row">
        <h3>Show Supplier</h3>
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">

                    <h4 class="mb-3">{{ $supplier->supplier_name }}</h4>
                    <hr/>

                    <p><strong>Pic Supplier:</strong> {{ $supplier->pic_supplier ?? '-' }}</p>
                    <hr/>

                    <p><strong>Email:</strong> {{ $supplier->supplier_email ?? '-' }}</p>
                    <hr/>

                    <p><strong>Phone:</strong> {{ $supplier->supplier_phone ?? '-' }}</p>
                    <hr/>

                    <p><strong>Address:</strong> {{ $supplier->supplier_address ?? '-' }}</p>
                    <hr/>

                    <p><strong>Dibuat pada:</strong> {{ $supplier->created_at ? $supplier->created_at->format('d M Y, H:i') : '-' }}</p>
                    <p><strong>Terakhir diupdate:</strong> {{ $supplier->updated_at ? $supplier->updated_at->format('d M Y, H:i') : '-' }}</p>

                    <hr/>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">Kembali</a>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
