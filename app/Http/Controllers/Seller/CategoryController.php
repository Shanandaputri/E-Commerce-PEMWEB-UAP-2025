<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // LIST KATEGORI
    public function index()
    {
        // nanti kalau mau per store tinggal filter by store_id
        $categories = ProductCategory::latest()->get();

        return view('seller.categories.index', compact('categories'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('seller.categories.create');
    }

    // SIMPAN KATEGORI BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        // upload gambar kalau ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images/categories', 'public');
        }

        ProductCategory::create($validated);

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // FORM EDIT
    public function edit(ProductCategory $category)
    {
        return view('seller.categories.edit', compact('category'));
    }

    // UPDATE
    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images/categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    // HAPUS
    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('seller.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function show(Category $category){
    $categories = Category::withCount('products')->get();
    $products   = $category->products()
                    ->with(['productImages', 'productReviews'])
                    ->paginate(9);

    return view('category.show', compact('category', 'categories', 'products'));
}

}
