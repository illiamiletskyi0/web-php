<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogCategory;
use App\Http\Resources\Api\Blog\CategoryResource;
use App\Http\Resources\Api\Blog\CategoryDetailResource;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BlogCategory::with(['parentCategory:id,title'])->get();

        if (request()->is('api/*')) {
            return CategoryResource::collection($items);
        }

        return view('blog.categories.index', compact('items'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = BlogCategory::with(['parentCategory:id,title'])->find($id);

        if (!$item) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        return new CategoryDetailResource($item);
    }
}
