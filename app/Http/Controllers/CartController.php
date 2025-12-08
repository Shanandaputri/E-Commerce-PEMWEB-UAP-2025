<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate([
            'color' => 'required|string',
            'size'  => 'required|string',
            'qty'   => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        // key unik per kombinasi product + size + color
        $key = $product->id.'_'.$data['size'].'_'.$data['color'];

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $data['qty'];
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'color'      => $data['color'],
                'size'       => $data['size'],
                'qty'        => $data['qty'],
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Product berhasil ditambahkan ke keranjang.');
    }
}
