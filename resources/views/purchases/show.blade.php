<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Detail Pembelian</h2></x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">{{ session('success') }}</div>
                @endif

                <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                    <p><span class="text-gray-500">No. PO:</span> {{ $purchase->po_number }}</p>
                    <p><span class="text-gray-500">Tanggal:</span> {{ $purchase->order_date->format('d M Y') }}</p>
                    <p><span class="text-gray-500">Supplier:</span> {{ $purchase->supplier->name }}</p>
                    <p><span class="text-gray-500">Admin penginput:</span> {{ $purchase->user->name }}</p>
                    <p class="col-span-2"><span class="text-gray-500">Catatan:</span> {{ $purchase->note ?? '-' }}</p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Produk</th>
                            <th class="px-3 py-2 text-right">Qty</th>
                            <th class="px-3 py-2 text-right">Harga Beli</th>
                            <th class="px-3 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($purchase->details as $detail)
                            <tr>
                                <td class="px-3 py-2">{{ $detail->product->name }}</td>
                                <td class="px-3 py-2 text-right">{{ $detail->qty }}</td>
                                <td class="px-3 py-2 text-right">Rp{{ number_format($detail->cost_price, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold">
                            <td colspan="3" class="px-3 py-2 text-right">Total</td>
                            <td class="px-3 py-2 text-right">Rp{{ number_format($purchase->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                <a href="{{ route('purchases.index') }}" class="inline-block mt-4 text-indigo-600 text-sm">&larr; Kembali ke daftar</a>
            </x-card>
        </div>
    </div>
</x-app-layout>