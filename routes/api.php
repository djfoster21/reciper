<?php

use App\Http\Controllers\api\AuthorizationController;
use App\Http\Controllers\api\ImportController;
use App\Http\Controllers\api\RecipeController;
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
Route::post('login', [AuthorizationController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'import'], function () {
        Route::post('recipes', [ImportController::class, 'recipes'])->name('import.recipes');
        Route::post('costs', [ImportController::class, 'costs'])->name('import.costs');
    });
    Route::group(['prefix' => 'recipes'], function () {
        Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');
        Route::post('/', [RecipeController::class, 'store'])->name('recipes.store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });
});
