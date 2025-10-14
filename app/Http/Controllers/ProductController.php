<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category_product;
use App\Models\Supplier;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(Request $request): View
{
    // Mulai dengan metode get_product() dari model Anda yang sudah melakukan join
    $product_model = new Product;
    $productsQuery = $product_model->get_product();

    // Terapkan logika pencarian pada query yang sudah di-join
    if ($request->filled('search')) {
        $searchTerm = $request->input('search');
        $productsQuery->where(function($query) use ($searchTerm) {
            $query->where('products.title', 'like', '%' . $searchTerm . '%')
                  // GANTI 'cp' menjadi 'category_product'
                  ->orWhere('category_product.product_category_name', 'like', '%' . $searchTerm . '%') 
                  // GANTI 's' menjadi 'supplier'
                  ->orWhere('supplier.supplier_name', 'like', '%' . $searchTerm . '%');
        });
    }

    // Ambil hasil akhir dengan pagination
    $products = $productsQuery->latest('products.created_at')->paginate(10);

    return view('products.index', compact('products'));
}


    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        $category_product = new Category_product;
        $supplier = new Supplier;

        $data['categories'] = $category_product->get_category_product()->get();
        $data['suppliers'] = $supplier->get_supplier();

        return view('products.create', compact('data'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // var_dump($request); exit;
        //validate form
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',    
            'title' => 'required|min:5',
            'supplier_id'   => 'required|integer',
            'product_category_id' => 'required|integer',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $store_image = $image->store('images', 'public');

            $product = new product;
            $insert_product = $product->storeProduct($request, $image);

            return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }

        return redirect()->route('products.index')->with(['error' => 'Failed to upload image (request).']);
    }

    /**
    * @param mixed Sid
    * @return View
    */
    public function show(string $id): View
    {
        //get product by ID
        $product_model = new Product;
        $product = $product_model->get_product()->where("products.id", $id)->firstOrFail();

        //render view with product
        return view('products.show', compact('product'));
    }

    /**
    * edit
    *
    * @param mixed Sid
    * @return View
    */
    public function edit(string $id): View
    {
        //get product by ID
        $product_model = new Product;
        $data['product'] = $product_model->get_product()->where("products.id", $id)->firstOrFail();
        
        $category_product = new Category_product;
        $supplier = new Supplier;
        $data['categories'] = $category_product->get_category_product()->get();
        $data['suppliers'] = $supplier->get_supplier();
   
        return view('products.edit', compact('data'));
    }

    /**
    * update
    *
    * @param mixed $request
    * @param mixed $id
    * @return RedirectResponse
    */
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        //get product by ID
        $product_model = new Product;
        $nama_gambar = null;

        if ($request->hasFile('image')) {
            
            $image = $request->file('image');
            $store_image = $image->store('images', 'public'); 
            $nama_gambar = $image->hashName();

            $data_product = $product_model->get_product()->where("products.id", $id)->firstOrFail();
            
            Storage::disk('public')->delete('images/'.$data_product->image);
            
        } 
            $request_data = [
                'title'                 => $request->title,
                'product_category_id'   => $request->product_category_id,
                'supplier_id'           => $request->supplier_id,
                'description'           => $request->description,
                'price'                 => $request->price,
                'stock'                 => $request->stock
            ];

            $update_product = $product_model->updateProduct($id, $request, $nama_gambar);

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
    * @param mixed Sid
    * @return RedirectResponse
    */
    public function destroy($id): RedirectResponse
    {
        $product_model = new Product;
        $product = $product_model->get_product()->where("products.id", $id)->firstOrFail();

        Storage::disk('public')->delete('images/'.$product->image);

        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}