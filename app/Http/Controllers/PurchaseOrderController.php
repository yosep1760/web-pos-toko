<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier', 'user')
            ->latest()->paginate(10);

        return view('purchases.index', compact('orders'));
    }

    public function create()
    {
        return view('purchases.create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'products'  => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'order_date'           => 'required|date',
            'note'                 => 'nullable|string|max:255',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   =>
                'required|exists:products,id|distinct',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.cost_price'   => 'required|integer|min:0',
        ]);

        $order = DB::transaction(function () use ($request) {
            $total = 0;
            $details = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()
                    ->findOrFail($item['product_id']);

                $subtotal = $item['qty'] * $item['cost_price'];
                $total += $subtotal;

                $details[] = [
                    'product_id' => $product->id,
                    'qty'        => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal'   => $subtotal,
                ];

                $product->increment('stock', $item['qty']);
            }

            $order = PurchaseOrder::create([
                'po_number'   => 'PO-' . now()->format('Ymd') . '-' .
                    str_pad(PurchaseOrder::max('id') + 1, 4, '0',
                        STR_PAD_LEFT),
                'supplier_id' => $request->supplier_id,
                'user_id'     => auth()->id(),
                'order_date'  => $request->order_date,
                'total'       => $total,
                'note'        => $request->note,
            ]);

            $order->details()->createMany($details);

            return $order;
        });

        return redirect()->route('purchases.show', $order)
            ->with('success', 'Pembelian berhasil dicatat, stok ditambah.');
    }

    public function show(PurchaseOrder $purchase)
    {
        $purchase->load('supplier', 'user', 'details.product');

        return view('purchases.show', compact('purchase'));
    }

    public function destroy(PurchaseOrder $purchase)
    {
        try {
            DB::transaction(function () use ($purchase) {
                foreach ($purchase->details as $detail) {
                    $product = Product::lockForUpdate()
                        ->findOrFail($detail->product_id);

                    if ($product->stock < $detail->qty) {
                        throw new \Exception("Stok {$product->name} " .
                            'sudah terjual, pembelian tidak bisa ' .
                            'dibatalkan.');
                    }

                    $product->decrement('stock', $detail->qty);
                }

                $purchase->delete();
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('purchases.index')
            ->with('success', 'Pembelian dibatalkan, stok dikembalikan.');
    }
}