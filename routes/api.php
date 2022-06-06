<?php

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
// ---- регистрация
Route::post('/signup', [App\Http\Controllers\API\UserController::class, 'signup']);
// ---- авторизация
Route::post('/login', [App\Http\Controllers\API\UserController::class, 'login']);
// ---- получение всех категорий
Route::get('/category', [App\Http\Controllers\API\CategoryController::class, 'getAll']);
// ---- получение всех товаров по категории
Route::get('/category/product/{id}', [App\Http\Controllers\API\CategoryController::class, 'prodInCategory']);
// ---- получение всех продуктов
Route::get('/product', [App\Http\Controllers\API\ProductController::class, 'getAll']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });

    // ---- категории
    Route::post('/category', [App\Http\Controllers\API\CategoryController::class, 'create']);
    Route::patch('/category/{id}', [App\Http\Controllers\API\CategoryController::class, 'update']);
    Route::delete('/category/{id}', [App\Http\Controllers\API\CategoryController::class, 'delete']);
    // --------------

    // ---- категории
    Route::post('/product', [App\Http\Controllers\API\ProductController::class, 'create']);
    Route::patch('/product/{id}', [App\Http\Controllers\API\ProductController::class, 'update']);
    Route::delete('/product/{id}', [App\Http\Controllers\API\ProductController::class, 'delete']);
    // --------------

    // ---- выход
    Route::get('/logout', function (Request $request) {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Вы успешно вышли со своего аккаунта'], 200);
    });
});

