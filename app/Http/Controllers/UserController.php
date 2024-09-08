<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the user's dashboard with wallet balance.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $carts = Transaction::where('status', 'on cart')->get();
        $saldo = 0;

        // Check if user is logged in
        if(Auth::check()){
            $saldo = $this->checkSaldo();
        }

        return view('welcome', compact('carts', 'saldo'));
    }

    /**
     * Search transactions in the cart.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $carts = Transaction::where('status', 'on cart')->get();
        $keyword = $request->keyword;
        $saldo = 0;

        // Check if user is logged in
        if(Auth::check()){
            $saldo = $this->checkSaldo();
        }

        return view('welcome', compact('carts', 'saldo'));
    }

    
    /**
     * Check the user's saldo.
     *
     * @return float
     */
    public function checkSaldo()
    {
        $saldo = 0;

        $wallets = Wallet::where('user_id', Auth::user()->id)
                        ->where('status', 'success')
                        ->get();
        foreach($wallets as $wallet){
            $income = $wallet->income;
            $outcome = $wallet->outcome;
            $recent = $income - $outcome;
            $saldo += $recent;
        }

        return $saldo;
    }

    /**
     * Top up the user's saldo.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function topUpSaldo(Request $request)
    {
        Wallet::create([
            'user_id' => Auth::user()->id,
            'income' => $request->nominal,
            'description' => 'top up saldo',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('status', 'Berhasil melakukan permintaan topUp Saldo');
    }

    /**
     * Tarik Tunai function to withdraw user's saldo.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function TarikTunai(Request $request)
{
    // Temukan wallet user
    $userWallet = Wallet::where('user_id', Auth::user()->id)
                        ->where('status', 'success') // Wallet dengan status success
                        ->first();
    
    // Cek apakah saldo cukup untuk penarikan
    if ($userWallet && $userWallet->balance >= $request->nominal) {
        // Buat catatan transaksi tarik tunai tanpa mengurangi saldo
        Wallet::create([
            'user_id' => Auth::user()->id,
            'outcome' => $request->nominal,
            'description' => 'Tarik Tunai',
            'status' => 'pending', // Status awal pending sampai disetujui oleh bank
        ]);

        return redirect()->back()->with('status', 'Berhasil melakukan permintaan Tarik Tunai, menunggu persetujuan dari bank.');
    } else {
        return redirect()->back()->with('error', 'Saldo tidak cukup untuk melakukan penarikan tunai');
    }
}
}
