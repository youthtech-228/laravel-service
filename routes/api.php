<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TaskApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('project')->group(function() {
    Route::get('/', [ProjectApiController::class, 'index']);
    Route::get('/{id}', [ProjectApiController::class, 'edit']);
    Route::post('/', [ProjectApiController::class, 'store']);
    Route::put('/{id}', [ProjectApiController::class, 'update']);
    Route::delete('/{id}', [ProjectApiController::class, 'delete']);
});
Route::prefix('task')->group(function() {
    Route::get('/{projectId}', [TaskApiController::class, 'index']);
    Route::get('/edit/{id}', [TaskApiController::class, 'edit']);
    Route::post('/', [TaskApiController::class, 'store']);
    Route::post('/sort', [TaskApiController::class, 'sort']);
    Route::put('/{id}', [TaskApiController::class, 'update']);
    Route::delete('/{id}', [TaskApiController::class, 'delete']);
});
