<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\UserBalance;
use App\Models\StoreBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal    = $carts->sum(fn ($c) => $c->product->price * $c->quantity);
        $deliveryFee = 2299;
        $total       = $subtotal + $deliveryFee;

        $userBalance = UserBalance::where('user_id', auth()->id())->first();

        return view('checkout.index', compact('carts', 'subtotal', 'deliveryFee', 'total', 'userBalance'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string',
            'address'        => 'required|string',
            'payment_method' => 'required|in:wallet,va',
        ]);

        $carts = Cart::with('product.store')->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal    = $carts->sum(fn ($c) => $c->product->price * $c->quantity);
        $deliveryFee = 2299;
        $grandTotal  = $subtotal + $deliveryFee;

        DB::beginTransaction();

        try {
            $paymentStatus  = 'unpaid';
            $vaNumber       = null;
            $paymentMethod  = $request->payment_method;

            // 1. Jika bayar pakai Wallet (E-Wallet)
            if ($paymentMethod === 'wallet') {
                $userBalance = UserBalance::where('user_id', auth()->id())->lockForUpdate()->firstOrFail();

                if ($userBalance->balance < $grandTotal) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tidak cukup. Silakan top up terlebih dahulu.');
                }

                // potong saldo user
                $userBalance->decrement('balance', $grandTotal);
                $paymentStatus = 'paid';
            }

            // 2. Jika bayar via VA langsung, generate VA unik
            if ($paymentMethod === 'va') {
                // contoh generate VA simple (sebaiknya diatur sesuai format kampus / tugas)
                $vaNumber = '8888' . now()->format('ymd') . rand(1000, 9999);
                $paymentStatus = 'unpaid'; // akan dibayar di halaman payment
            }

            // 3. Buat Transaction
            $transaction = Transaction::create([
                'code'           => 'TRX-' . strtoupper(Str::random(10)),
                'buyer_id'       => auth()->id(),
                'store_id'       => $carts->first()->product->store_id,
                'recipient_name' => $request->recipient_name,
                'address'        => $request->address,
                'city'           => $request->city ?? 'Jakarta',
                'postal_code'    => $request->postal_code ?? '12345',
                'shipping'       => 'JNE',
                'shipping_type'  => 'REG',
                'shipping_cost'  => $deliveryFee,
                'tax'            => 0,
                'grand_total'    => $grandTotal,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'va_number'      => $vaNumber,
            ]);

            // 4. Detail transaksi
            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $cart->product_id,
                    'qty'            => $cart->quantity,
                    'subtotal'       => $cart->product->price * $cart->quantity,
                ]);

                // update stok
                $cart->product->decrement('stock', $cart->quantity);
            }

            // 5. Kalau sudah paid (wallet), tambahkan ke saldo toko
            if ($paymentStatus === 'paid') {
                $storeBalance = StoreBalance::firstOrCreate(
                    ['store_id' => $transaction->store_id],
                    ['balance'  => 0]
                );

                // biasanya yang masuk ke toko = subtotal (tanpa shipping)
                $storeBalance->increment('balance', $subtotal);
            }

            // 6. Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            if ($paymentMethod === 'va') {
                // diarahkan ke halaman detail pembayaran VA (challenge halaman Payment)
                return redirect()->route('payment.show', $transaction->va_number)
                    ->with('success', 'Order created. Silakan lakukan pembayaran via VA.');
            }

            // kalau wallet (langsung paid) → ke history
            return redirect()->route('customer.history')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }
}
