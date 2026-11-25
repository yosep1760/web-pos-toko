<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Akun Kasir - POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Akun Kasir</h1>
            <a href="{{ route('users.create') }}"
               class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Kasir
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-gray-100 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">Nama</th>
                    <th class="py-2">Email</th>
                    <th class="py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="py-2">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2 text-center">
                            <form action="{{ route('users.destroy', $user) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">
                            Belum ada akun kasir.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}"
           class="inline-block mt-4 text-sm text-gray-600">
            &laquo; Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
