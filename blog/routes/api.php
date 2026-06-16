<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
    Route::apiResource('categories', CategoryController::class)
        ->only(['index', 'show'])
        ->names('blog.categories');
});

$groupData = [
    'namespace' => 'App\Http\Controllers\Api\Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {
    //BlogCategory
    $methods = ['index','store','update','destroy',];
    Route::apiResource('categories', AdminCategoryController::class)
    ->only($methods)
    ->names('blog.admin.categories'); 
    Route::apiResource('posts', AdminPostController::class)
    ->except(['show'])
    ->names('blog.admin.posts');
});
