<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private function rules(): array
    {
        return [
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function index(Request $request)
    {
        $suppliers = Supplier::when($request->filled('search'),
                fn ($q) => $q->where('name', 'like',
                    '%' . $request->search . '%'))
            ->orderBy('name')->paginate(10)->withQueryString();

        return view('master-data.supplier.index',
            compact('suppliers'));
    }

    public function create()
    {
        return view('master-data.supplier.create');
    }

    public function store(Request $request)
    {
        Supplier::create($request->validate($this->rules()));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('master-data.supplier.edit',
            compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($request->validate($this->rules()));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchaseOrders()->exists()) {
            return back()->with('error',
                'Supplier sudah punya riwayat pembelian, ' .
                'tidak dapat dihapus.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}