<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
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
                'code' =>  404,
                'message' => 'یافت نشد مجددا سعی کنید'
            ], 404);
        }

        $sellers = $buyer->transactions()
            ->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }
}
