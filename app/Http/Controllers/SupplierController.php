<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'supplier_name' => 'required|min:3',
            'pic_supplier'  => 'required|min:3',
        ]);

        Supplier::create([
            'supplier_name' => $request->supplier_name,
            'pic_supplier'  => $request->pic_supplier,
        ]);

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

        $request->validate([
            'supplier_name' => 'required|min:3',
            'pic_supplier'  => 'required|min:3',
        ]);

        $supplier->update([
            'supplier_name' => $request->supplier_name,
            'pic_supplier'  => $request->pic_supplier,
        ]);

        return redirect()->route('suppliers.index')->with(['success' => 'Data Supplier Berhasil Diubah!']);
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with(['success' => 'Data Supplier Berhasil Dihapus!']);
    }
}