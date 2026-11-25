<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Tambah Supplier</h2></x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6">
            <x-card>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    @include('master-data.supplier._form')
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>