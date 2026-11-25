<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-sm w-full max-w-sm">
        <h1 class="text-xl font-semibold mb-6 text-center">POS Barokah Mart</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required autofocus>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md">
                Masuk
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-gray-200 text-xs text-gray-600 space-y-2">
            <p class="font-semibold text-gray-700 text-center">Akun Demo (Klik untuk isi otomatis)</p>
            <button type="button"
                onclick="document.getElementById('email').value='admin@barokahmart.test';document.getElementById('password').value='password';"
                class="w-full text-left p-2.5 rounded-md bg-gray-50 hover:bg-indigo-50 border border-gray-200 cursor-pointer">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-800">Admin Toko</span>
                    <span class="px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 font-medium">Admin</span>
                </div>
                <div class="text-gray-500 mt-0.5">Email: admin@barokahmart.test</div>
                <div class="text-gray-500">Password: password</div>
            </button>
            <button type="button"
                onclick="document.getElementById('email').value='kasir@barokahmart.test';document.getElementById('password').value='password';"
                class="w-full text-left p-2.5 rounded-md bg-gray-50 hover:bg-green-50 border border-gray-200 cursor-pointer">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-800">Kasir Rina</span>
                    <span class="px-1.5 py-0.5 rounded bg-green-100 text-green-700 font-medium">Kasir</span>
                </div>
                <div class="text-gray-500 mt-0.5">Email: kasir@barokahmart.test</div>
                <div class="text-gray-500">Password: password</div>
            </button>
        </div>
    </div>
</body>
</html>