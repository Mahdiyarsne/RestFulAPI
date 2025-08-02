<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::all();

        return $this->showAll($products);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'محصولی یافت نشد'
            ], 404);
        }


        return $this->showOne($product);
    }
}
