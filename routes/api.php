<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/test', function (){
    return json_encode(['I got here']);
});

Route::prefix('v1')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/user', function (Request $request) {return $request->user();});
        Route::prefix('products')->group(function(){
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{product}', [ProductController::class, 'get']);
            Route::post('/create', [ProductController::class, 'create']);
            Route::put('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'delete']);
        });
    });
});

