<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $suppliersQuery = Supplier::query();

    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $suppliersQuery->where('supplier_name', 'like', '%' . $searchTerm . '%')
                       ->orWhere('pic_supplier', 'like', '%' . $searchTerm . '%')
                       ->orWhere('supplier_email', 'like', '%' . $searchTerm . '%');
    }

    $suppliers = $suppliersQuery->latest()->paginate(10);

    return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {

         $request->validate([
            'supplier_name'    => 'required|min:3',
            'pic_supplier'     => 'nullable|string',
            'supplier_email'   => 'nullable|email',
            'supplier_phone'   => 'nullable|numeric',
            'supplier_address' => 'nullable|string',
        ]);
        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with(['success' => 'Data Supplier Berhasil Disimpan!']);
    }

    public function show(Supplier $supplier): View
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        // 1. Validasi semua input
        $request->validate([
            'supplier_name'    => 'required|min:3',
            'pic_supplier'     => 'nullable|string',
            'supplier_email'   => 'nullable|email',
            'supplier_phone'   => 'nullable|numeric',
            'supplier_address' => 'nullable|string',
        ]);
        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with(['success' => 'Data Supplier Berhasil Diubah!']);
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with(['success' => 'Data Supplier Berhasil Dihapus!']);
    }
}