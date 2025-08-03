<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        //
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'پرداخت دسته بندی یافت نشد'
            ], 404);
        }

        $transactions = $category->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();


        return $this->showAll($transactions);
    }
}
