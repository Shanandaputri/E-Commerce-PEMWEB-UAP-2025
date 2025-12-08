<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBalance;
use App\Models\WalletTransaction;
use App\Models\Transaction;
use App\Models\StoreBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // ================= WALLET OVERVIEW =================
    public function index()
    {
        $user = Auth::user();

        $balance = UserBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $transactions = $balance->transactions()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('wallet.index', [
            'wallet'       => $balance,
            'transactions' => $transactions,
        ]);
    }

    // ================= HALAMAN TOPUP =================
    public function topup()
    {
        return view('wallet.topup');
    }

    public function storeTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:10000000',
        ]);

        $user = Auth::user();

        $wallet = UserBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $vaNumber = '1234'
            . str_pad($user->id, 4, '0', STR_PAD_LEFT)
            . rand(1000, 9999);

        $transaction = WalletTransaction::create([
            'wallet_id'        => $wallet->id,
            'transaction_type' => 'topup',
            'amount'           => $request->amount,
            'status'           => 'pending',
            'va_number'        => $vaNumber,
            'description'      => 'Top Up Saldo',
        ]);

        return response()->json([
            'success'        => true,
            'va_number'      => $vaNumber,
            'amount'         => $request->amount,
            'transaction_id' => $transaction->id,
        ]);
    }

    // ============== HALAMAN PAYMENT TERPUSAT ==============
    public function paymentForm()
    {
        $vaNumber = session('va_number');
        $vaAmount = session('va_amount');

        return view('wallet.payment', [
            'vaNumber' => $vaNumber,
            'vaAmount' => $vaAmount,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'va_number' => 'required|string',
            'amount'    => 'required|numeric|min:1',
        ]);

        $sessionVa  = session('va_number');
        $sessionAmt = session('va_amount');
        $trxId      = session('va_transaction_id');

        if (!$sessionVa || $request->va_number !== $sessionVa) {
            return back()->with('error', 'Nomor VA tidak sesuai.');
        }

        if ((int) $request->amount !== (int) $sessionAmt) {
            return back()->with('error', 'Nominal pembayaran tidak sesuai.');
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($trxId);
            $transaction->update([
                'payment_status' => 'paid',
            ]);

            $subtotal = $transaction->grand_total - $transaction->shipping_cost;

            $storeBalance = StoreBalance::firstOrCreate(
                ['store_id' => $transaction->store_id],
                ['balance'  => 0]
            );
            $storeBalance->increment('balance', $subtotal);

            session()->forget(['va_number', 'va_amount', 'va_transaction_id']);

            DB::commit();

            return redirect()
                ->route('customer.history')
                ->with('success', 'Pembayaran VA berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
        }
    }
}
