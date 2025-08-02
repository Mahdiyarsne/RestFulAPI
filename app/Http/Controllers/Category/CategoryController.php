<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return $this->showAll($categories);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'sataus' => 'ناموفق',
                'message' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $newCategory = Category::create($data);

        return $this->showOne($newCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $category->fill($request->only([
            'name',
            'description'
        ]));

        if ($category->isClean()) {
            return $this->errorResponse('شما باید یک تغییری انجام بدی تا اسم و توضیحات تغییر کند', 422);
        }

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return $this->showOne($category);
    }
}
