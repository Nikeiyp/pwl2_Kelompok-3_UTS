<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Edit Supplier</h3>
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <form id="supplierForm" action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">SUPPLIER NAME</label>
                            <input type="text" 
                                   class="form-control @error('supplier_name') is-invalid @enderror" 
                                   name="supplier_name" 
                                   value="{{ old('supplier_name', $supplier->supplier_name) }}" 
                                   placeholder="Masukkan Nama Supplier" 
                                   required>
                            @error('supplier_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">PIC SUPPLIER</label>
                            <input type="text" 
                                   class="form-control @error('pic_supplier') is-invalid @enderror" 
                                   name="pic_supplier" 
                                   value="{{ old('pic_supplier', $supplier->pic_supplier) }}" 
                                   placeholder="Masukkan Nama PIC Supplier" 
                                   required>
                            @error('pic_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">EMAIL</label>
                            <input type="email" 
                                   class="form-control @error('supplier_email') is-invalid @enderror" 
                                   name="supplier_email" 
                                   value="{{ old('supplier_email', $supplier->supplier_email) }}" 
                                   placeholder="Masukkan Email Supplier" 
                                   required>
                            @error('supplier_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">PHONE</label>
                            <input type="text" 
                                   class="form-control @error('supplier_phone') is-invalid @enderror" 
                                   name="supplier_phone" 
                                   value="{{ old('supplier_phone', $supplier->supplier_phone) }}" 
                                   placeholder="Masukkan Nomor Telepon Supplier" 
                                   required>
                            @error('supplier_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">ADDRESS</label>
                            <textarea class="form-control @error('supplier_address') is-invalid @enderror" 
                                      name="supplier_address" 
                                      rows="4" 
                                      placeholder="Masukkan Alamat Supplier" 
                                      required>{{ old('supplier_address', $supplier->supplier_address) }}</textarea>
                            @error('supplier_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-md btn-primary me-3">UPDATE</button>
                        <button type="button" onclick="resetForm()" class="btn btn-md btn-warning">RESET</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-md btn-secondary">BACK</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function resetForm() {
        document.getElementById("supplierForm").reset();
    }
</script>
</body>
</html>
