<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sellers = Seller::has('products')->get();

        return $this->showAll($sellers);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $seller = Seller::has('products')->find($id);
        if (!$seller) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'Message' => 'یافت نشد'
            ], 404);
        }
        return $this->showOne($seller);
    }
}
