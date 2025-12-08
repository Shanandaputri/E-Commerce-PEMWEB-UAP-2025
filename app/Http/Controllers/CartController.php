<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart (sesuai desain figma).
     */
    public function index()
    {
        $carts = Cart::with('product.productImages')
            ->where('user_id', Auth::id())
            ->get();

        // hitung subtotal
        $subtotal = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // contoh delivery fee flat 2.299 IDR
        $deliveryFee = 2299;
        $total       = $subtotal + $deliveryFee;

        return view('cart.index', compact('carts', 'subtotal', 'deliveryFee', 'total'));
    }

    /**
     * Tambah ke cart via form (misalnya dari product detail).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'size'       => ['nullable', 'string'],
            'color'      => ['nullable', 'string'],
        ]);

        $cart = Cart::firstOrCreate(
            [
                'user_id'    => Auth::id(),
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

    /**
     * Tambah ke cart dari route /cart/add/{product}
     * (misal tombol "Add to Cart" di card homepage).
     */
    public function add(Product $product)
    {
        $cart = Cart::firstOrCreate(
            [
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'size'       => null,
                'color'      => null,
            ],
            [
                'quantity' => 0,
            ]
        );

        $cart->increment('quantity');

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Update quantity (+ / −) di cart page.
     */
    public function update(Request $request, Cart $cart)
    {
        // pastikan ini cart milik user yang login
        abort_if($cart->user_id !== Auth::id(), 403);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index');
    }

    /**
     * Hapus item dari cart.
     */
    public function destroy(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
}
