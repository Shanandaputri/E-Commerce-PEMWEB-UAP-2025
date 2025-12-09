<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBalance;
use App\Models\WalletTransaction;
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

        $transactions = WalletTransaction::where('wallet_id', $balance->id)
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

        // Generate VA Number
        $vaNumber = '1234'
            . str_pad($user->id, 4, '0', STR_PAD_LEFT)
            . rand(1000, 9999);

        // Create pending transaction
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

    // ============== HALAMAN PAYMENT ==============
    public function paymentForm(Request $request)
    {
        $vaNumber = $request->query('va');
        $vaAmount = $request->query('amount');
        $transactionId = $request->query('transaction_id');

        if (!$vaNumber || !$vaAmount) {
            return redirect()->route('wallet.index')
                ->with('error', 'Data pembayaran tidak valid.');
        }

        return view('wallet.payment', [
            'vaNumber' => $vaNumber,
            'vaAmount' => $vaAmount,
            'transactionId' => $transactionId,
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'va_number' => 'required|string',
            'amount'    => 'required|numeric|min:10000',
            'transaction_id' => 'required|exists:wallet_transactions,id',
        ]);

        DB::beginTransaction();
        try {
            // Find transaction
            $transaction = WalletTransaction::findOrFail($request->transaction_id);

            // Validasi
            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaksi sudah diproses sebelumnya.');
            }

            if ($transaction->va_number !== $request->va_number) {
                throw new \Exception('Nomor VA tidak sesuai.');
            }

            if ((float) $transaction->amount !== (float) $request->amount) {
                throw new \Exception('Nominal pembayaran tidak sesuai.');
            }

            // Update transaction status
            $transaction->update(['status' => 'success']);

            // Update wallet balance
            $wallet = UserBalance::findOrFail($transaction->wallet_id);
            $wallet->increment('balance', $transaction->amount);

            DB::commit();

            return redirect()
                ->route('wallet.index')
                ->with('success', 'Top up berhasil! Saldo Anda telah ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}