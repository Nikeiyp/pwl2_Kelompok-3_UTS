<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ViewErrorBag;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //get all suppliers
        $supplier = new Supplier;
        $suppliers = $supplier->get_supplier()->latest()->paginate(10);

        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

     /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request) : RedirectResponse

    {
        $validated = $request->validate([
        'supplier_name' => 'required|string|max:100',
        'pic_supplier' => 'required|string|max:100',
        'supplier_email' => 'required|email',
        'supplier_phone' => 'required|string|max:20',
        'supplier_address' => 'required|string'
    ]);

    Supplier::create($validated);

    return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
         //get supplier by ID
        $supplier_model = new Supplier;
        $supplier = $supplier_model->get_supplier()->where("supplier.id", $id)->firstOrFail();

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        $supplier_model = new Supplier;
        $supplier = $supplier_model->get_supplier()->where("supplier.id", $id)->firstOrFail();

        return view('suppliers.edit', compact('supplier')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_name'   => 'required|string|max:100',
            'pic_supplier'    => 'required|string|max:100',
            'supplier_email'  => 'required|email|max:100',
            'supplier_phone'  => 'required|string|max:20',
            'supplier_address'=> 'required|string|max:255'
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        //get supplier by ID
        $supplier_model = new Supplier;
        $supplier = $supplier_model->get_supplier()->where("supplier.id", $id)->firstOrFail();

        

        //delete product
        $supplier->delete();

        //redirect to index
        return redirect()->route('suppliers.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }


}
