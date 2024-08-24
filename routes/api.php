<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('checkAuth', [AuthController::class, 'checkAuth'])->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);



     /**
     * Source API
     */
    
     Route::get('/task/{task}', [TaskController::class, 'gettask']);
     Route::post('/task', [TaskController::class, 'storetask']);
     Route::put('/task/{task}', [TaskController::class, 'updatetask']);
     Route::delete('/task/{task}', [TaskController::class, 'deletetask']);
 
});
