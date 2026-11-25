<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Data Supplier</h2>
            <a href="{{ route('suppliers.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">+ Tambah Supplier</a>
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

                <form method="GET" class="mb-4 flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama supplier..."
                           class="rounded-md border-gray-300 text-sm">
                    <button class="px-4 py-2 border rounded-md text-sm">Cari</button>
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">Nama</th>
                            <th class="px-4 py-2 text-left text-xs">Telepon</th>
                            <th class="px-4 py-2 text-left text-xs">Alamat</th>
                            <th class="px-4 py-2 text-center text-xs">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="px-4 py-2">{{ $supplier->name }}</td>
                                <td class="px-4 py-2">{{ $supplier->phone }}</td>
                                <td class="px-4 py-2">{{ $supplier->address }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                       class="text-indigo-600">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Hapus supplier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $suppliers->links() }}</div>
            </x-card>
        </div>
    </div>
</x-app-layout>