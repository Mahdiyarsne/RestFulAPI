<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
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

        $categories = $seller->products()
            ->whereHas('categories')
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            ->unique('id')
            ->values();
        return  $this->showAll($categories);
    }
}
