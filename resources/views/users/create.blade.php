<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Kasir - POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-sm">
        <h1 class="text-xl font-semibold mb-4">Tambah Akun Kasir</h1>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 block w-full rounded-md border-gray-300">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 block w-full rounded-md border-gray-300">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm">Password</label>
                <input type="password" name="password"
                       class="mt-1 block w-full rounded-md border-gray-300">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('users.index') }}"
                   class="text-sm text-gray-600">Batal</a>
                <button type="submit"
                        class="bg-gray-800 text-white px-4 py-2 rounded-md">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</body>
</html>
