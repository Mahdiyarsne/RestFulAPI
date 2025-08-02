<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $buyer = Buyer::has('transactions')->find($id);
        if (!$buyer) {

            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'یافت نشد دوباره تلاش کنید'
            ], 404);
        }
        return $this->showOne($buyer);
    }
}
