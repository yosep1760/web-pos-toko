<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Soal 1: filter status stok
        $threshold = config('pos.low_stock_threshold', 10);

        if ($request->stock_status === 'habis') {
            $query->where('stock', 0);
        } elseif ($request->stock_status === 'menipis') {
            $query->where('stock', '>', 0)
                  ->where('stock', '<', $threshold);
        }

        // Soal 2: jumlah data per halaman
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $products = $query->latest()->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('master-data.product.index',
            compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('master-data.product.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['transactionDetails.transaction']);

        $totalTerjual = $product->transactionDetails->sum('qty');

        $riwayat = $product->transactionDetails()
            ->with('transaction')
            ->latest()
            ->take(10)
            ->get();

        // Latihan 2: 5 hari dengan penjualan tertinggi
        $hariTerlaris = $product->transactionDetails()
            ->join('transactions', 'transactions.id', '=',
                   'transaction_details.transaction_id')
            ->selectRaw('DATE(transactions.created_at) as tanggal')
            ->selectRaw('SUM(transaction_details.qty) as total_qty')
            ->selectRaw('SUM(transaction_details.subtotal) as total_omzet')
            ->groupBy('tanggal')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('master-data.product.show',
            compact('product', 'totalTerjual', 'riwayat', 'hariTerlaris'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('master-data.product.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Soft delete menjaga referensi produk pada riwayat transaksi.
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}