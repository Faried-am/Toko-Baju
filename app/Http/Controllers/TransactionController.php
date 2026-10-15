<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();

        return view('transactions.index', [
            'transactions' => $transactions
        ]);
    }
    public function create()
{
    return view('transactions.create');
}

public function store(Request $request)
{
    Transaction::create([
        'title' => $request->title,
        'type' => $request->type,
        'amount' => $request->amount,
        'date' => $request->date,
    ]);

    return redirect('/transactions');
}
}