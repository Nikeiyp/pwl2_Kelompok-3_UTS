<?php

namespace App\Http\Controllers;

use App\Models\Category_product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryProductController extends Controller
{
    public function index(Request $request): View
    {
        $category_products = Category_product::query();
        
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $category_products->where('product_category_name', 'like', '%' . $searchTerm . '%');
        }

        $category_products = $category_products->latest()->paginate(10);

        return view('category_products.index', compact('category_products'));
    }

    public function create(): View
    {
        return view('category_products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['product_category_name' => 'required|min:3']);

        Category_product::create(['product_category_name' => $request->product_category_name]);

        return redirect()->route('category_products.index')->with(['success' => 'Kategori Produk Berhasil Disimpan!']);
    }

    public function edit(Category_product $category_product): View
    {
        return view('category_products.edit', compact('category_product'));
    }

    public function update(Request $request, Category_product $category_product): RedirectResponse
    {
        $request->validate(['product_category_name' => 'required|min:3']);

        $category_product->update(['product_category_name' => $request->product_category_name]);

        return redirect()->route('category_products.index')->with(['success' => 'Kategori Produk Berhasil Diubah!']);
    }

    public function destroy(Category_product $category_product): RedirectResponse
    {
        $category_product->delete();
        return redirect()->route('category_products.index')->with(['success' => 'Kategori Produk Berhasil Dihapus!']);
    }
}