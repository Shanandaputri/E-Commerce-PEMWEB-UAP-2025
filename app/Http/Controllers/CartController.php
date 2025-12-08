<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product.productImages')
            ->where('user_id', auth()->id())
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // contoh: flat 2.299 IDR
        $deliveryFee = 2299;
        $total       = $subtotal + $deliveryFee;

        return view('cart.index', compact('carts', 'subtotal', 'deliveryFee', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'size'       => 'nullable|string',
            'color'      => 'nullable|string',
        ]);

        // kalau product yang sama + size/color sama sudah ada di cart, tinggal update quantity
        $cart = Cart::firstOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'size'       => $request->size,
                'color'      => $request->color,
            ],
            [
                'quantity' => 0,
            ]
        );

        $cart->increment('quantity', $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Cart $cart)
    {
        // pastikan cart ini milik user login
        abort_if($cart->user_id !== auth()->id(), 403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index');
    }

    public function destroy(Cart $cart)
    {
        abort_if($cart->user_id !== auth()->id(), 403);

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
}
