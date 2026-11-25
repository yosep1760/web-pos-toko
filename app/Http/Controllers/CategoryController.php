<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('master-data.category.index', compact('categories'));
    }

    public function create()
    {
        return view('master-data.category.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Category $category)
    {
        $products = $category->products()
            ->orderBy('name')
            ->get();

        $totalStok = $products->sum('stock');

        return view('master-data.category.show',
            compact('category', 'products', 'totalStok'));
    }

    public function edit(Category $category)
    {
        return view('master-data.category.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $jumlahProduk = $category->products()->count();

        if ($jumlahProduk > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori {$category->name} tidak dapat "
                    . "dihapus karena masih memiliki {$jumlahProduk} produk.");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}