<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with([
                'productImages',
                'productReviews',
                'store',
                'productCategory',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = ProductCategory::all();

        return view('product.show', compact('product', 'categories'));
    }
}
