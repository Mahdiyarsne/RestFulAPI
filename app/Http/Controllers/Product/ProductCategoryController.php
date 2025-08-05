<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
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

        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, Category $category)
    {
        //

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'دوباره تلاش کنید.'
            ], 404);
        }

        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
        //
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('این محصول در این دسته بندی وجود ندارد', 404);
        }

        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);
    }
}
