<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Middleware\TransformInput;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;


class ProductBuyerTransactionController extends ApiController
{

    public function __construct()
    {

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store', 'update']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $validator = Validator::make($request->all(), rules: [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'ناموفق',
                'message' => $validator->errors()
            ], 400);
        }
        // 
        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('خریدار باید از فروشنده جدا باشد', 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse('خریدار باید یک کاربر تایید شده باشد.', 409);
        }


        if (!$product->seller->isVerified()) {
            return $this->errorResponse('فروشنده باید یک کاربر تایید شده باشد.', 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse('محصول در دسترس نمی باشد.', 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('این تعداد برای این تراکنش کافی نمی باشد.', 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transacton = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            return $this->showOne($transacton, 201);
        });
    }
}
