@php $s = $supplier ?? null; @endphp

@foreach ([
    'name' => 'Nama Supplier', 'phone' => 'Telepon',
    'email' => 'Email', 'address' => 'Alamat',
] as $field => $text)
    <div class="mb-4">
        <label class="block text-sm text-gray-700">{{ $text }}</label>
        <input type="text" name="{{ $field }}"
               value="{{ old($field, $s?->$field) }}"
               class="mt-1 w-full rounded-md border-gray-300">
        @error($field)
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
@endforeach

<button class="bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan</button>