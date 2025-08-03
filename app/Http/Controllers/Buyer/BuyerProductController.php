<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
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
                'message' => 'خریدار یافت نشد,مجددا تلاش کنید'
            ], 404);
        }

        $products = $buyer->transactions()
            ->with('product')
            ->get()
            ->pluck('product');
        return $this->showAll($products);
    }
}
