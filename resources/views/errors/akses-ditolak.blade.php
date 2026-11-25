<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Akses Ditolak - POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-sm max-w-md text-center">
        <p class="text-5xl font-semibold text-gray-800">403</p>
        <h1 class="text-xl font-semibold mt-2">Akses Ditolak</h1>
        <p class="text-gray-600 mt-3">
            Anda tidak memiliki akses ke halaman ini.
            Halaman ini khusus untuk: {{ implode(', ', $dibutuhkan) }}.
        </p>
        @auth
            <p class="text-sm text-gray-500 mt-2">
                Anda login sebagai {{ auth()->user()->name }}
                ({{ auth()->user()->role }}).
            </p>
        @endauth
        <a href="{{ route('dashboard') }}"
           class="inline-block mt-6 bg-gray-800 text-white px-4 py-2 rounded-md text-sm">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
