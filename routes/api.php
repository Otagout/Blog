<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PostController;


use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/posts', [PostController::class, 'index']); // Fetch all posts
Route::get('/posts/{post}', [PostController::class, 'show']); // Fetch a single post by ID
Route::get('/categories', [CategoryController::class, 'index']); // Fetch all posts
Route::get('/categories/{category}', [CategoryController::class, 'show']); // Fetch a single post by ID
Route::get('/comments', [CommentController::class, 'index']); // Fetch all posts
Route::get('/comments/{comment}', [CommentController::class, 'show']); // Fetch all posts
Route::post('/comments/store', [CommentController::class, 'store']); // Fetch all posts
Route::post('/send-message' , [InvitationController::class , 'sendInvitation']);

Route::delete('/deleteComment/{id}' , [CommentController::class , 'deleteComment']);



// Authenticated user route
Route::middleware(['auth:sanctum,admin'])->get('/user', function (Request $request) {
    return $request->user();
});
// Admin routes
Route::middleware(['auth:sanctum,admin'  ])->prefix('admin')->group(function () {
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);
    Route::apiResource('comments', CommentController::class)->except(['index', 'show','store']);

 });
//
// Authentication routes
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);