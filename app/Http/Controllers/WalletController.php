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
    // WALLET OVERVIEW
    public function index()
    {
        $user = Auth::user();

        $balance = UserBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $transactions = WalletTransaction::where('wallet_id', $balance->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('wallet.index', [
            'wallet'       => $balance,
            'transactions' => $transactions,
        ]);
    }

    //HALAMAN TOPUP
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

        // Generate VA Number untuk TOPUP
        $vaNumber = '1234'
            . str_pad($user->id, 4, '0', STR_PAD_LEFT)
            . rand(1000, 9999);

        // Create pending wallet transaction
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

    // HALAMAN PAYMENT
    public function paymentForm(Request $request)
    {
        $source = $request->query('source', 'topup');

        // MODE CHECKOUT
        if ($source === 'checkout') {
            $vaNumber      = $request->query('va');
            $vaAmount      = $request->query('amount');
            $transactionId = $request->query('transaction_id');

            if (!$vaNumber || !$vaAmount || !$transactionId) {
                return redirect()->route('customer.history')
                    ->with('error', 'Data pembayaran tidak valid.');
            }

            return view('wallet.payment', [
                'mode'          => 'checkout',
                'vaNumber'      => $vaNumber,
                'vaAmount'      => $vaAmount,
                'transactionId' => $transactionId,
            ]);
        }

        // MODE TOPUP saldo e-wallet
        $vaNumber      = $request->query('va');
        $vaAmount      = $request->query('amount');
        $transactionId = $request->query('transaction_id');

        if (!$vaNumber || !$vaAmount || !$transactionId) {
            return redirect()->route('wallet.index')
                ->with('error', 'Data pembayaran tidak valid.');
        }

        return view('wallet.payment', [
            'mode'          => 'topup',
            'vaNumber'      => $vaNumber,
            'vaAmount'      => $vaAmount,
            'transactionId' => $transactionId,
        ]);
    }

    // KONFIRMASI PEMBAYARAN
    public function confirmPayment(Request $request)
    {
        $mode = $request->input('mode', 'topup');

        $request->validate([
            'va_number'      => 'required|string',
            'amount'         => 'required|numeric|min:10000',
            'transaction_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            if ($mode === 'topup') {
                $transaction = WalletTransaction::findOrFail($request->transaction_id);

                if ($transaction->status !== 'pending') {
                    throw new \Exception('Transaksi sudah diproses sebelumnya.');
                }

                if ($transaction->va_number !== $request->va_number) {
                    throw new \Exception('Nomor VA tidak sesuai.');
                }

                if ((float) $transaction->amount !== (float) $request->amount) {
                    throw new \Exception('Nominal pembayaran tidak sesuai.');
                }

                // Update status transaksi
                $transaction->update(['status' => 'success']);

                // Tambah saldo wallet user
                $wallet = UserBalance::findOrFail($transaction->wallet_id);
                $wallet->increment('balance', $transaction->amount);

                DB::commit();

                return redirect()
                    ->route('wallet.index')
                    ->with('success', 'Top up berhasil! Saldo Anda telah ditambahkan.');
            }

            // MODE CHECKOUT
            $trx = Transaction::findOrFail($request->transaction_id);

            if ($trx->payment_status === 'paid') {
                throw new \Exception('Transaksi ini sudah dibayar.');
            }

            if ($trx->payment_method !== 'va') {
                throw new \Exception('Metode pembayaran tidak valid untuk transaksi ini.');
            }

            if ($trx->va_number !== $request->va_number) {
                throw new \Exception('Nomor VA tidak sesuai.');
            }

            if ((float) $trx->grand_total !== (float) $request->amount) {
                throw new \Exception('Nominal pembayaran tidak sesuai.');
            }

            // tandai transaksi sebagai paid
            $trx->update([
                'payment_status' => 'paid',
            ]);

            // tambahkan saldo ke toko
            $storeBalance = StoreBalance::firstOrCreate(
                ['store_id' => $trx->store_id],
                ['balance'  => 0]
            );

            // saldo yang masuk = total produk
            $productTotal = $trx->transactionDetails()->sum('subtotal');
            $storeBalance->increment('balance', $productTotal);

            DB::commit();

            return redirect()
                ->route('customer.history')
                ->with('success', 'Pembayaran pesanan berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
