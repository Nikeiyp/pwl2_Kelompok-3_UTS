<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div>
                <h3 class="text-center my-4">DATA SUPPLIER - TOKO JUALAN HP SECOND</h3>
                <hr>
            </div>

            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <a href="{{ route('suppliers.create') }}" class="btn btn-md btn-success mb-3">TAMBAH SUPPLIER</a>
                    <table class="table table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col" style="width:5%">NO</th>
                                <th scope="col">Supplier Name</th>
                                <th scope="col">Person In Charge</th>
                                <th scope="col">Email</th>
                                <th scope="col">Telephone No.</th>
                                <th scope="col">Address</th>
                                <th scope="col" style="width:20%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $supplier->supplier_name }}</td>
                                <td>{{ $supplier->pic_supplier ?? '-' }}</td>
                                <td>{{ $supplier->supplier_email ?? '-' }}</td>
                                <td>{{ $supplier->supplier_phone ?? '-' }}</td>
                                <td>{{ $supplier->supplier_address ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('suppliers.show', $supplier->id) }}" 
                                       class="btn btn-sm btn-dark">SHOW</a>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" 
                                       class="btn btn-sm btn-primary">EDIT</a>

                                    <form id="hapus-form-{{ $supplier->id }}" 
                                          action="{{ route('suppliers.destroy', $supplier->id) }}" 
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->supplier_name }}')">
                                            HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Supplier Belum Ada</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Bootstrap & SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi sukses/gagal
    @if(session('success'))
        Swal.fire({
            icon: "success",
            title: "BERHASIL",
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @elseif(session('error'))
        Swal.fire({
            icon: "error",
            title: "GAGAL",
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Konfirmasi hapus pakai SweetAlert
    function confirmDelete(id, name) {
        Swal.fire({
            title: "Yakin hapus supplier " + name + " ?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapus-form-' + id).submit();
            }
        });
    }
</script>

</body>
</html>
