<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection {
        // return response()->json(['message' => 'Hello, world!']);
        return CategoryResource::collection(Category::query()->orderBy('id', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) {
        //
    }
}
