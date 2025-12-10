<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        // asumsi di model ProductCategory ada relasi products()
        $categories = ProductCategory::withCount('products')
            ->latest()
            ->paginate(10);

        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        ProductCategory::create($data);

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }


    public function edit(ProductCategory $category)
    {
        return view('seller.categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
