<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // HALAMAN LIST SEMUA KATEGORI
    public function index()
    {
        // semua kategori untuk sidebar
        $categories = ProductCategory::withCount('products')->get();

        // produk ditampilkan semua
        $products = Product::with(['productImages', 'productReviews'])->paginate(9);
        
        $category = null;

        return view('category.show', compact('category', 'categories', 'products'));
    }

    // HALAMAN DETAIL KATEGORI
    public function show($id)
    {
        // kategori yang dipilih
        $category = ProductCategory::findOrFail($id);

        // semua kategori untuk sidebar
        $categories = ProductCategory::withCount('products')->get();

        // produk yang hanya milik kategori ini
        $products = Product::where('product_category_id', $id)
            ->with(['productImages', 'productReviews'])
            ->paginate(9);

        return view('category.show', compact('category', 'categories', 'products'));
    }
}
