<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\UserBalance;
use App\Models\StoreBalance;
use App\Models\Address;
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

        $carts = Cart::with('product.store')
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal    = $carts->sum(fn ($c) => $c->product->price * $c->quantity);
        $deliveryFee = 2299;
        $grandTotal  = $subtotal + $deliveryFee;

        $paymentMethod = $request->payment_method;
        $paymentStatus = 'unpaid';   // default
        $vaNumber      = null;

        DB::beginTransaction();

        try {
            // =============== BAYAR PAKAI WALLET (E-WALLET) ===============
            if ($paymentMethod === 'wallet') {
                $userBalance = UserBalance::where('user_id', auth()->id())
                    ->lockForUpdate()
                    ->first();

                if (!$userBalance) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo belum tersedia. Silakan top up dulu.');
                }

                if ($userBalance->balance < $grandTotal) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo tidak cukup. Silakan top up terlebih dahulu.');
                }

                // potong saldo user
                $userBalance->decrement('balance', $grandTotal);
                $paymentStatus = 'paid';
            }

            // =============== AMBIL / BUAT ALAMAT (addresses) ===============
            $address = Address::where('user_id', auth()->id())
                ->where('is_primary', true)
                ->first();

            if (!$address) {
                $address = Address::create([
                    'user_id'     => auth()->id(),
                    'label'       => 'Alamat Checkout',
                    'recipient'   => $request->recipient_name,
                    'phone'       => '081234567890',
                    'address'     => $request->address,
                    'city'        => 'Jakarta',
                    'province'    => 'DKI Jakarta',
                    'postal_code' => '12345',
                    'is_primary'  => true,
                ]);
            } else {
                $address->update([
                    'recipient' => $request->recipient_name,
                    'address'   => $request->address,
                ]);
            }

            // =============== BUAT TRANSACTION ===============
            $transaction = Transaction::create([
                'code'            => 'TRX-' . strtoupper(Str::random(10)),
                'buyer_id'        => auth()->id(),
                'store_id'        => $carts->first()->product->store_id,
                'address_id'      => $address->id,
                'address'         => $address->address,
                'city'            => $address->city,
                'postal_code'     => $address->postal_code,
                'shipping'        => 'JNE',
                'shipping_type'   => 'REG',
                'shipping_cost'   => $deliveryFee,
                'tracking_number' => null,
                'tax'             => 0,
                'grand_total'     => $grandTotal,
                'payment_status'  => $paymentStatus,
            ]);

            // =============== DETAIL TRANSAKSI ===============
            foreach ($carts as $cart) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $cart->product_id,
                    'qty'            => $cart->quantity,
                    'subtotal'       => $cart->product->price * $cart->quantity,
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            // =============== SALDO TOKO (KALAU SUDAH PAID) ===============
            if ($paymentStatus === 'paid') {
                $storeBalance = StoreBalance::firstOrCreate(
                    ['store_id' => $transaction->store_id],
                    ['balance'  => 0]
                );

                $storeBalance->increment('balance', $subtotal);
            }

            // Hapus cart user
            Cart::where('user_id', auth()->id())->delete();

            // ====== GENERATE VA JIKA METODE VA ======
            if ($paymentMethod === 'va') {
                $vaNumber = '888' . random_int(10000000, 99999999);

                session([
                    'va_number'         => $vaNumber,
                    'va_amount'         => $grandTotal,
                    'va_transaction_id' => $transaction->id,
                ]);
            }

            DB::commit();

            // Kalau VA → ke halaman Payment
            if ($paymentMethod === 'va') {
                return redirect()
                    ->route('payment.form')
                    ->with('success', 'Order dibuat. Silakan lakukan pembayaran via VA di halaman Payment.');
            }

            // Kalau Wallet → langsung ke history
            return redirect()
                ->route('customer.history')
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }
}
