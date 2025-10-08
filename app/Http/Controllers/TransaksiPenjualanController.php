<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksiPenjualan;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransaksiPenjualanController extends Controller
{
    public function index(): View
    {
        $transaksis = TransaksiPenjualan::with('details.product')->latest()->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    public function create(): View
    {
        $products = Product::orderBy('title')->get();
        return view('transaksi.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kasir' => 'required|string|max:50',
            'email_pembeli' => 'nullable|email',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat transaksi utama
            $transaksi = TransaksiPenjualan::create([
                'nama_kasir' => $request->nama_kasir,
                'email_pembeli' => $request->email_pembeli,
                'tanggal_transaksi' => now()
            ]);

            // 2. Simpan detail produk dan update stok
            foreach ($request->products as $productData) {
                $product = Product::find($productData['id']);

                if ($product->stock < $productData['jumlah']) {
                    throw new \Exception('Stok produk ' . $product->title . ' tidak mencukupi.');
                }

                DetailTransaksiPenjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_product' => $productData['id'],
                    'jumlah_pembelian' => $productData['jumlah'],
                ]);

                // Kurangi stok produk
                $product->decrement('stock', $productData['jumlah']);
            }

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }
}