<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Middleware\TransformInput;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Traits\FileUploadTraits;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Routing\Controllers\Middleware;

class SellerProductController extends ApiController
{
    use FileUploadTraits;

    public function __construct()
    {

        $this->middleware('transform.input:' . ProductTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */


    public function index(int $id)
    {
        //
        $seller = Seller::find($id);
        if (!$seller) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'فروشنده یافت نشد.دوباره تلاش کنید.'
            ], 404);
        }

        $products = $seller->products;
        return $this->showAll($products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $seller)
    {
        //
        $validator = Validator::make($request->all(), rules: [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ]);

        if ($validator->failed()) {
            return response()->json([
                'status ' => 'ناموفق',
                'message' => $validator->errors()
            ], 400);
        }


        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $imagePath = $this->uploadImage($request, 'image');
        $data['image'] = isset($imagePath) ? $imagePath : '';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        //
        $validator = Validator::make($request->all(), rules: [
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'image' => 'required|image'
        ]);

        if (!$validator->fails()) {
            return response()->json([
                'status' => 'ناموفق',
                'message' => $validator->errors()
            ], 400);
        }
        $this->checkeSeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;
            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have at least a categpry', 409);
            }
        }

        if ($request->hasFile('image')) {
            $this->removeImage($product->image);

            $imagePath = $this->uploadImage($request, 'image');
            $product->image = isset($imagePath) ? $imagePath : '';
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to change some values to update', 422);
        }

        $product->save();
        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller, int $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'ناموفق',
                'code' => 404,
                'message' => 'محصول یافت نشد.دوباره تلاش کنید.'
            ], 404);
        }

        $this->checkeSeller($seller, $product);
        $product->delete();
        $this->removeImage($product->image);
        return $this->showOne($product);
    }

    protected function checkeSeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, 'the spcified seller is not the actual seller of the product');
        }
    }
}
