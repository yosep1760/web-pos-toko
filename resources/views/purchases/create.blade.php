<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Pembelian Stok Baru</h2></x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6">
            <x-card>
                <form action="{{ route('purchases.store') }}" method="POST"
                      x-data="{
                          rows: [{ product_id: '', qty: 1, cost_price: 0 }],
                          add() { this.rows.push({ product_id: '', qty: 1, cost_price: 0 }); },
                          remove(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },
                          total() { return this.rows.reduce((s, r) => s + r.qty * r.cost_price, 0); }
                      }">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">{{ $errors->first() }}</div>
                    @endif

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <select name="supplier_id" class="rounded-md" required>
                            <option value="">-- Pilih supplier --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="order_date" class="rounded-md"
                               value="{{ old('order_date', now()->toDateString()) }}">
                        <input type="text" name="note" placeholder="Catatan" value="{{ old('note') }}"
                               class="rounded-md">
                    </div>

                    <div class="grid grid-cols-12 gap-2 mb-1 text-xs text-gray-500">
                        <span class="col-span-6">Produk</span>
                        <span class="col-span-2">Qty</span>
                        <span class="col-span-3">Harga Beli (Rp)</span>
                    </div>

                    <template x-for="(row, i) in rows" :key="i">
                        <div class="grid grid-cols-12 gap-2 mb-2">
                            <select :name="'items[' + i + '][product_id]'"
                                    x-model="row.product_id" required
                                    class="col-span-6 rounded-md">
                                <option value="">-- Pilih produk --</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" min="1" x-model.number="row.qty"
                                   :name="'items[' + i + '][qty]'"
                                   class="col-span-2 rounded-md">
                            <input type="number" min="0" x-model.number="row.cost_price"
                                   :name="'items[' + i + '][cost_price]'"
                                   class="col-span-3 rounded-md">
                            <button type="button" @click="remove(i)"
                                    class="col-span-1 text-red-600">X</button>
                        </div>
                    </template>

                    <button type="button" @click="add()"
                            class="text-indigo-600 text-sm">+ Tambah barang</button>

                    <p class="mt-4 font-semibold">Total: Rp<span
                        x-text="total().toLocaleString('id-ID')"></span></p>

                    <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan Pembelian</button>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>