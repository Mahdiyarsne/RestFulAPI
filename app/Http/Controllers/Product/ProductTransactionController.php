<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'محصول مورد نظر یافت نشد',
            ], 404);
        }


        $transactions = $product->transactions;
        return $this->showAll($transactions);
    }
}
