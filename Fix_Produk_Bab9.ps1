$ErrorActionPreference = 'Stop'

# Jalankan dari folder root proyek Laravel (folder yang berisi file artisan).
if (-not (Test-Path -LiteralPath '.\artisan')) {
    throw 'Folder proyek Laravel tidak ditemukan. Buka terminal VS Code pada folder yang berisi file artisan, lalu jalankan ulang.'
}

$root = (Get-Location).Path
$utf8 = New-Object System.Text.UTF8Encoding($false)
$backupRoot = Join-Path $root ('_backup_fix_produk_' + (Get-Date -Format 'yyyyMMdd_HHmmss'))
$filesToFix = @(
    'app\Http\Controllers\ProductController.php',
    'app\Http\Requests\UpdateProductRequest.php'
)

foreach ($relativePath in $filesToFix) {
    $fullPath = Join-Path $root $relativePath
    if (-not (Test-Path -LiteralPath $fullPath)) {
        throw "File tidak ditemukan: $relativePath. Pastikan Anda menjalankan script di proyek POS Barokah Mart yang benar."
    }
    $backupPath = Join-Path $backupRoot $relativePath
    New-Item -ItemType Directory -Path (Split-Path -Parent $backupPath) -Force | Out-Null
    Copy-Item -LiteralPath $fullPath -Destination $backupPath -Force
}

$controller = @'
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('master-data.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('master-data.product.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('master-data.product.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Soft delete menjaga referensi produk pada riwayat transaksi.
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
'@

$updateRequest = @'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('products', 'code')->ignore($product),
            ],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:20'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Kode produk sudah digunakan, gunakan kode lain.',
            'price.min' => 'Harga produk harus lebih dari 0.',
        ];
    }
}
'@

[System.IO.File]::WriteAllText((Join-Path $root 'app\Http\Controllers\ProductController.php'), $controller, $utf8)
[System.IO.File]::WriteAllText((Join-Path $root 'app\Http\Requests\UpdateProductRequest.php'), $updateRequest, $utf8)
Write-Host 'OK: Controller produk dan validasi update sudah diperbaiki.' -ForegroundColor Green
Write-Host "Backup file lama: $backupRoot" -ForegroundColor Cyan

# Migration hanya menjalankan perubahan yang belum pernah dijalankan.
$migration = Join-Path $root 'database\migrations\2026_11_16_225912_add_soft_deletes_to_products_table.php'
if (Test-Path -LiteralPath $migration) {
    php artisan migrate --force
    if ($LASTEXITCODE -ne 0) {
        throw 'Migration gagal. Kode controller tetap sudah diperbaiki; periksa koneksi database dan pesan error di atas.'
    }
}

php artisan optimize:clear
if ($LASTEXITCODE -ne 0) {
    throw 'Pembersihan cache Laravel gagal. Lihat pesan error di atas.'
}

php -l app/Http/Controllers/ProductController.php
if ($LASTEXITCODE -ne 0) { throw 'Syntax ProductController masih bermasalah.' }
php -l app/Http/Requests/UpdateProductRequest.php
if ($LASTEXITCODE -ne 0) { throw 'Syntax UpdateProductRequest masih bermasalah.' }

Write-Host "`nMemeriksa route produk:" -ForegroundColor Cyan
php artisan route:list --path=products --except-vendor
if ($LASTEXITCODE -ne 0) { throw 'Pemeriksaan route gagal.' }

Write-Host "`nSELESAI. Buka kembali /products, lalu uji tombol Ubah dan Hapus." -ForegroundColor Green
