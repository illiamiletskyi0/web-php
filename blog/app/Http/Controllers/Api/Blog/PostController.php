<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use App\Http\Resources\Api\Blog\PostResource;
use App\Http\Resources\Api\Blog\PostDetailResource;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BlogPost::with('category')->get();

        if (request()->is('api/*')) {
            return PostResource::collection($items);
        }

        return view('blog.posts.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = BlogPost::with(['category', 'user'])->find($id);

        if (!$item) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        return new PostDetailResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
