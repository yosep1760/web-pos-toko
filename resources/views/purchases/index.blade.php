<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Pembelian Stok</h2>
            <a href="{{ route('purchases.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">+ Pembelian Baru</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">{{ session('error') }}</div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">No. PO</th>
                            <th class="px-4 py-2 text-left text-xs">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs">Supplier</th>
                            <th class="px-4 py-2 text-right text-xs">Total</th>
                            <th class="px-4 py-2 text-center text-xs">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="px-4 py-2">{{ $order->po_number }}</td>
                                <td class="px-4 py-2">{{ $order->order_date->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $order->supplier->name }}</td>
                                <td class="px-4 py-2 text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('purchases.show', $order) }}"
                                       class="text-indigo-600">Detail</a>
                                    <form action="{{ route('purchases.destroy', $order) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Batalkan pembelian {{ $order->po_number }}? Stok akan dikurangi kembali.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-2">Batalkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada pembelian stok.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $orders->links() }}</div>
            </x-card>
        </div>
    </div>
</x-app-layout>