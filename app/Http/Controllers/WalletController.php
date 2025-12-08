<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Auto create wallet
        $wallet = $user->wallet ?? Wallet::create([
            'user_id' => $user->id,
            'balance' => 0
        ]);

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function topup()
    {
        return view('wallet.topup');
    }

    public function storeTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:10000000'
        ]);

        $user = auth()->user();
        $wallet = $user->wallet;

        // Generate VA Number
        $vaNumber = '1234' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . rand(1000, 9999);

        // Create transaction
        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'transaction_type' => 'topup',
            'amount' => $request->amount,
            'status' => 'pending',
            'va_number' => $vaNumber,
            'description' => 'Top Up Saldo'
        ]);

        return response()->json([
            'success' => true,
            'va_number' => $vaNumber,
            'amount' => $request->amount,
            'transaction_id' => $transaction->id
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $transaction = WalletTransaction::find($request->transaction_id);
        
        if ($transaction && $transaction->status === 'pending') {
            // Update transaction status
            $transaction->update(['status' => 'success']);
            
            // Update wallet balance
            $wallet = $transaction->wallet;
            $wallet->increment('balance', $transaction->amount);

            return response()->json([
                'success' => true,
                'new_balance' => $wallet->balance
            ]);
        }

        return response()->json(['success' => false], 400);
    }
}