<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Pastikan user memiliki role sebelum mengecek nama role
        if (Auth::check() && Auth::user()->roles->count() > 0) {
            $user = Auth::user();
            $wallet = Wallet::where('user_id', $user->id)->first();
            $saldo = $wallet ? $wallet->balance : 0; // Ambil saldo dari wallet jika ada, jika tidak ada set ke 0

            if ($user->roles[0]->name == 'bank') {
                // Ambil wallet request dengan status pending
                $wallet_requests = Wallet::where('status', 'pending')->get();
                
                // Ambil history wallet dengan status success
                $historyWallets = Wallet::where('status', 'success')->get();

                return view('home', compact('wallet_requests', 'historyWallets', 'saldo'));
            } else {
                // Jika role bukan 'bank', bisa mengarahkan ke halaman lain atau menampilkan halaman default
                return view('home', compact('saldo')); // Kirimkan saldo ke view
            }
        } 
    }

    /**
     * Accept wallet request
     */
    public function acceptWalletRequest($id)
{
    $walletRequest = Wallet::find($id);

    if ($walletRequest) {
        // Jika request penarikan tunai (outcome)
        if ($walletRequest->outcome > 0) {
            // Temukan wallet user yang sesuai
            $userWallet = Wallet::where('user_id', $walletRequest->user_id)
                                ->where('status', 'success')
                                ->first();

            // Pastikan wallet user ditemukan dan saldo cukup
            if ($userWallet && $userWallet->balance >= $walletRequest->outcome) {
                // Kurangi saldo user berdasarkan outcome dari permintaan
                $userWallet->balance -= $walletRequest->outcome;
                $userWallet->save();

                // Update status permintaan
                $walletRequest->update([
                    'status' => 'success',
                ]);

                return redirect()->back()->with('status', 'Berhasil menerima permintaan Tarik Tunai');
            } else {
                return redirect()->back()->with('error', 'Saldo tidak cukup untuk menerima permintaan Tarik Tunai');
            }
        }

        // Jika request adalah top-up (income)
        if ($walletRequest->income > 0) {
            // Temukan wallet user yang sesuai
            $userWallet = Wallet::where('user_id', $walletRequest->user_id)
                                ->first();

            // Jika wallet user ditemukan
            if ($userWallet) {
                // Tambah saldo berdasarkan income dari permintaan
                $userWallet->balance += $walletRequest->income;
                $userWallet->save();
            }

            // Update status permintaan
            $walletRequest->update([
                'status' => 'success',
            ]);

            return redirect()->back()->with('status', 'Berhasil menerima permintaan Top Up');
        }
    }

    return redirect()->back()->with('error', 'Permintaan tidak ditemukan');
}


    /**
     * Reject wallet request
     */
    public function rejectWalletRequest($id)
    {
        $wallet = Wallet::find($id);

        if ($wallet) {
            $wallet->delete();

            return redirect()->back()->with('status', 'Berhasil menolak permintaan');
        }

        return redirect()->back()->with('error', 'Permintaan tidak ditemukan');
    }
}
