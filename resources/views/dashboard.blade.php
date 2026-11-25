<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <h3 class="text-lg font-semibold mb-2">Ringkasan Hari Ini</h3>
                <p class="text-gray-600">
                    Selamat datang, {{ auth()->user()->name }}.
                </p>
            </x-card>

            {{-- Contoh sementara untuk menguji komponen x-badge --}}
            <x-card>
                <h3 class="text-lg font-semibold mb-3">Contoh Status Stok</h3>
                <div class="flex gap-8 text-sm">
                    <div>Stok 25 <x-badge :stock="25" /></div>
                    <div>Stok 5 <x-badge :stock="5" /></div>
                    <div>Stok 0 <x-badge :stock="0" /></div>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>