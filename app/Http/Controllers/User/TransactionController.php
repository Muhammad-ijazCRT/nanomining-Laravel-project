<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;


class TransactionController extends Controller
{
    public function AllTransaction()
    {
        $pageTitle     = 'All Transaction';
        $user          = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)->orderBy('id', 'desc')->limit(10)->get();
        // return $transactions;

        // return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'transactions', 'user'));
        return view($this->activeTemplate . 'user.transaction.all_transaction', compact('pageTitle', 'transactions', 'user'));
    }
    public function CurrentWeekTransaction()
    {
        $pageTitle = 'Current Week Transactions';
        $user = auth()->user();

        // Get the start and end dates of the current week
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();

        // Query transactions for the current week
        $transactions = Transaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->limit(10)
            ->get();

        return view($this->activeTemplate . 'user.transaction.current_week', compact('pageTitle', 'transactions', 'user',));
    }


    public function CurrentMonthTransaction()
    {
        $pageTitle = 'Current Month Transaction';
        $user = auth()->user();

        // Get the current month and year
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');

        // Query transactions for the current month
        $transactions = Transaction::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->limit(10)
            ->get();

        // ->orderBy('id', 'desc')
        return view($this->activeTemplate . 'user.user.transaction.current_month', compact('pageTitle', 'transactions', 'user',));
    }
}
