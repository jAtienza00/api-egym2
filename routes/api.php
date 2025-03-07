<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ClasesController;
use App\Http\Controllers\api\UsuarioPy;

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

Route::resource('clases', ClasesController::class);
Route::resource('usuarios', UsuarioPy::class);
Route::fallback(function () {
    return response()->json(['error' => 'No encontrado'], 404);
  });
