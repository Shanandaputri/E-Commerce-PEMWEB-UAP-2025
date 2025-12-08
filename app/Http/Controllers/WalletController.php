<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBalance;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    // ================= WALLET OVERVIEW =================
    public function index()
    {
        $user = Auth::user();

        // Auto-create saldo user kalau belum ada
        $balance = UserBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        // Ambil 10 transaksi terakhir
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

    // Dipanggil via fetch() dari halaman topup
    public function storeTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:10000000',
        ]);

        $user = Auth::user();

        // Pastikan user_balance ada
        $wallet = UserBalance::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        // Generate nomor VA
        $vaNumber = '1234'
            . str_pad($user->id, 4, '0', STR_PAD_LEFT)
            . rand(1000, 9999);

        // Buat transaksi TOPUP dengan status pending
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
    // GET /payment
    public function paymentForm(Request $request)
    {
        return view('wallet.payment', [
            'prefilledVa'     => $request->query('va'),
            'prefilledAmount' => $request->query('amount'),
        ]);
    }

    // POST /payment/confirm
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'va_number' => 'required|string',
            'amount'    => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();

        // Cari transaksi topup pending dengan VA tsb milik user ini
        $transaction = WalletTransaction::where('va_number', $request->va_number)
            ->where('transaction_type', 'topup')
            ->where('status', 'pending')
            ->whereHas('wallet', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (! $transaction) {
            return back()->withErrors([
                'va_number' => 'Transaksi dengan nomor VA tersebut tidak ditemukan atau sudah diproses.',
            ])->withInput();
        }

        // pengecekan nominal harus sama
        if ((float) $request->amount !== (float) $transaction->amount) {
            return back()->withErrors([
                'amount' => 'Nominal pembayaran tidak sesuai dengan tagihan.',
            ])->withInput();
        }

        // Update status transaksi
        $transaction->update(['status' => 'success']);

        // Tambah saldo user
        $wallet = $transaction->wallet; // relasi ke UserBalance
        $wallet->increment('balance', $transaction->amount);

        return redirect()
            ->route('wallet.index')
            ->with('success', 'Top up berhasil dikonfirmasi.');
    }
}
