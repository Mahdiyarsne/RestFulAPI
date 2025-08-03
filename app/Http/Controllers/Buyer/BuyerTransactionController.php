<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        //
        $buyer = Buyer::find($id);

        if (!$buyer) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'خریدار یافت نشد'
            ], 404);
        }

        $transactions = $buyer->transactions;

        return $this->showAll($transactions);
    }
}
