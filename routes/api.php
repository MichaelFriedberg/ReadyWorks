<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComputerController;

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

Route::get('/chart/top10', [ComputerController::class, 'getTopTenComputerModels']);
Route::get('/chart/os', [ComputerController::class, 'getComputersByOperatingSystem']);
Route::get('/chart/location', [ComputerController::class, 'getComputersByLocation']);
Route::get('/table', [ComputerController::class, 'dataTable']);
