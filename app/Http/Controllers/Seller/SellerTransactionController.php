<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        //
        $seller = Seller::find($id);
        if (!$seller) {
            return response()->json([
                'status' => 'ناموقق',
                'code' => 404,
                'message' => 'تراکنش فروشنده یافت نشد'
            ], 404);
        }

        $transactions = $seller->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();
            
        return $this->showAll($transactions);
    }
}
