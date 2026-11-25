# POS Barokah Mart

Aplikasi Point of Sale (POS) berbasis web yang dibangun menggunakan **Laravel** untuk membantu operasional toko kelontong **Barokah Mart**—mulai dari pengelolaan data master, pembelian stok (*purchase order*), transaksi kasir, cetak struk thermal 58mm, laporan penjualan dan ekspor Excel, hingga dashboard analitik.

## Live Demo

- **URL Aplikasi:** https://pos-barokah-mart.onrender.com/
- **Hosting Aplikasi:** Render (Docker, Free Web Service)
- **Database Produksi:** TiDB Cloud Starter (MySQL Compatible)

> **Catatan:** Karena berjalan pada paket gratis Render, akses pertama setelah tidak aktif (*idle*) mungkin membutuhkan waktu sekitar 30–50 detik untuk menyalakan ulang server.

## Akun Demo

| Role | Nama | Email | Password |
| :--- | :--- | :--- | :--- |
| **Admin** | Admin Toko | admin@barokahmart.test | password |
| **Kasir** | Kasir Rina | kasir@barokahmart.test | password |

## Fitur Utama

- **Autentikasi & Otorisasi Bertingkat:** Login manual dengan Auth facade, middleware role:admin dan role:kasir, halaman error 403 kustom, serta navigasi dinamis.
- **Dashboard Analitik:** Ringkasan omzet hari ini/kemarin, jumlah transaksi, produk stok menipis, grafik tren 7 hari terakhir (Chart.js), dan 5 produk terlaris.
- **Manajemen Data Master (Admin):** CRUD Kategori (dengan jumlah produk & halaman detail), CRUD Produk (Soft Delete, status stok, filter/search, pagination, detail & 5 hari penjualan tertinggi), CRUD Supplier, dan kelola akun kasir.
- **Pembelian Stok / Purchase Order (Admin):** Form multi-item dengan Alpine.js, penambahan & pengembalian stok aman dengan DB::transaction() dan lockForUpdate().
- **Transaksi Penjualan / Layar Kasir (Admin & Kasir):** Keranjang belanja real-time dengan Alpine.js, validasi stok server-side, Riwayat Transaksi Saya khusus kasir, serta cetak struk standar dan thermal printer 58mm.
- **Laporan Penjualan & Export Excel (Admin):** Filter rentang tanggal & kasir, serta ekspor .xlsx 2 sheet (Transaksi dan Produk Terlaris) menggunakan Laravel Excel.

## Teknologi yang Digunakan

- **Backend:** PHP 8.2, Laravel
- **Frontend:** Blade Templates, Tailwind CSS, Vite, Alpine.js, Chart.js
- **Export Data:** Maatwebsite Laravel Excel
- **Database:** MySQL (lokal) & TiDB Cloud (produksi)
- **Deployment:** Docker, Render

## Menjalankan Secara Lokal

~~~bash
git clone https://github.com/yosep1760/web-pos-toko.git
cd web-pos-toko
composer install
npm install
cp .env.example .env
php artisan key:generate
~~~

Atur koneksi database MySQL pada file `.env`, lalu jalankan:

~~~bash
php artisan migrate --seed
npm run build
php artisan serve
~~~

Buka aplikasi di browser pada alamat http://127.0.0.1:8000.