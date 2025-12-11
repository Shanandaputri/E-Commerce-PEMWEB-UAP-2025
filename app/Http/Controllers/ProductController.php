<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

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

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $products = Product::query()
            ->where('name', 'like', '%' . $q . '%')
            ->with(['productImages' => function ($query) {
                $query->where('is_thumbnail', 1);
            }])
            ->limit(10)
            ->get();

        $results = $products->map(function ($product) {
            $thumb = optional($product->productImages->first())->image;

            return [
                'name'  => $product->name,
                'slug'  => $product->slug,
                'price' => number_format($product->price, 0, ',', '.'),
                'image' => $thumb ? asset($thumb) : null,
                'url'   => route('product.show', $product->slug),
            ];
        });

        return response()->json($results);
    }
}
