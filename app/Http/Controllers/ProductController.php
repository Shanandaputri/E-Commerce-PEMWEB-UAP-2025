<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
                'productImages',
                'store',
                'productCategory',
                'productReviews',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('product.show', compact('product'));
    }
}
