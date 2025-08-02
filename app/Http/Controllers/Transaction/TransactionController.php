<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();

        return $this->showAll($transactions);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        $transaction = Transaction::find($id);
        //
        if (!$transaction) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'پرداختی یافت نشد'
            ], 404);
        }
        return $this->showOne($transaction);
    }
}
