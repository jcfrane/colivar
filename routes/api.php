<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RegisterController;
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

Route::middleware('auth:sanctum')->get('/sample', function (Request $request) {
    return $request->user();
});

Route::controller(FileController::class)
    ->group(function() {
        Route::post('/upload', [FileController::class, 'upload'])->name('file.upload');
        Route::get('/download', [FileController::class, 'download'])->name('file.download');
    });


Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
