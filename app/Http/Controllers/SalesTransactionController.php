<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\SalesTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SalesTransactionController extends Controller
{
    public function index(): View
    {
        $transactions = SalesTransaction::latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $products = Product::where('stock', '>', 0)->orderBy('title')->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cashier_name'      => 'required|min:3',
            'products'          => 'required|array',
            'products.*.id'     => 'required',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        $grandTotal = 0;
        $transactionDetails = [];

        // Hitung total dan siapkan detailnya terlebih dahulu
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $price = $product->price;
            $subtotal = $price * $item['quantity'];
            $grandTotal += $subtotal;

            $transactionDetails[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $price,
                'subtotal'   => $subtotal,
            ];

            // Update stok
            $product->stock = $product->stock - $item['quantity'];
            $product->save();
        }
        
        // Buat transaksi utama dengan grand total
        $transaction = SalesTransaction::create([
            'cashier_name'    => $request->cashier_name,
            'customer_email'  => $request->customer_email,
            'grand_total'     => $grandTotal,
        ]);

        // Simpan detail transaksi
        foreach ($transactionDetails as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with(['success' => 'Transaction Created Successfully!']);
    }

    public function show(string $id): View
    {
        // Find the transaction and automatically fetch all related details
        // and the product for each detail. This is called "eager loading".
        $transaction = SalesTransaction::with('details.product')->findOrFail($id);

        // Now, pass the single $transaction object to the view.
        // The view will contain all the necessary data.
        return view('transactions.show', compact('transaction'));
    }

    public function edit(string $id): View
    {
        $transaction = SalesTransaction::find($id);
        $products = Product::orderBy('title')->get();
        
        $transaction->details = SalesTransactionDetail::where('sales_transaction_id', $id)->get();

        return view('transactions.edit', compact('transaction', 'products'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'cashier_name'      => 'required|min:3',
            'products'          => 'required|array',
            'products.*.id'     => 'required',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        $transaction = SalesTransaction::findOrFail($id);
        
        // Kembalikan stok lama
        foreach ($transaction->details as $oldDetail) {
            $product = Product::find($oldDetail->product_id);
            if ($product) {
                $product->stock += $oldDetail->quantity;
                $product->save();
            }
        }
        
        // Hapus detail lama
        $transaction->details()->delete();

        $grandTotal = 0;
        $newTransactionDetails = [];

        // Hitung ulang total dan siapkan detail baru
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $price = $product->price;
            $subtotal = $price * $item['quantity'];
            $grandTotal += $subtotal;

            $newTransactionDetails[] = [
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $price,
                'subtotal'   => $subtotal,
            ];
            
            // Kurangi stok baru
            $product->stock -= $item['quantity'];
            $product->save();
        }

        // Update transaksi utama
        $transaction->update([
            'cashier_name'   => $request->cashier_name,
            'customer_email' => $request->customer_email,
            'grand_total'    => $grandTotal,
        ]);
        
        // Simpan detail transaksi yang baru
        foreach ($newTransactionDetails as $detail) {
            $transaction->details()->create($detail);
        }

        return redirect()->route('transactions.index')->with(['success' => 'Transaction Updated Successfully!']);
    }

    public function destroy(string $id): RedirectResponse
    {
        $transaction = SalesTransaction::find($id);
        
        $detailItems = SalesTransactionDetail::where('sales_transaction_id', $transaction->id)->get();

        foreach ($detailItems as $item) {
            $product = Product::find($item->product_id);
            $product->stock = $product->stock + $item->quantity;
            $product->save();
        }

        SalesTransactionDetail::where('sales_transaction_id', $transaction->id)->delete();
        $transaction->delete();

        return redirect()->route('transactions.index')->with(['success' => 'Transaction Deleted Successfully!']);
    }
}