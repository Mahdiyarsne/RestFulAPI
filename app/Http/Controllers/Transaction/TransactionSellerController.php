<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'یافت نشد'
            ], 404);
        }

        //
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
    }
}
