<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
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
                'messsage' => 'دسته بندی محصولات یافت نشد'
            ], 404);
        }

        $products = $category->products;
        return $this->showAll($products);
    }
}
