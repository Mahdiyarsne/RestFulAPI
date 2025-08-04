<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
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

        $buyers = $seller->products()
            ->whereHas('transactions.buyer')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}
