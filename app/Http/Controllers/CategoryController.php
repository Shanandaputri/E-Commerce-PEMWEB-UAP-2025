<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id)
    {
        // Ambil kategori yang dipilih
        $category = ProductCategory::findOrFail($id);

        // Semua kategori untuk sidebar / dropdown
        $categories = ProductCategory::withCount('products')->get();

        // Produk-produk dalam kategori ini
        $products = Product::where('product_category_id', $id)
            ->with(['productImages', 'productReviews'])
            ->paginate(9);

        return view('category.show', compact('category', 'categories', 'products'));
    }
}
