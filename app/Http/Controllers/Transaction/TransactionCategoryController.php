<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Transaction;

class TransactionCategoryController extends ApiController
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
        $categories = $transaction->product->categories;
        if (!$categories) {
            return $this->errorResponse('یافت نشد', 404);
        }

        return $this->showAll($categories);
    }
}
