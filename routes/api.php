<?php

use App\Http\Controllers\Api\AkunController;
use App\Http\Controllers\Api\JurnalController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfilController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/akun', App\Http\Controllers\Api\AkunController::class);
Route::apiResource('/sub_akun', App\Http\Controllers\Api\SubAkunController::class);
Route::apiResource('/data_akun', App\Http\Controllers\Api\DataAkunController::class);
Route::apiResource('/transaksi', App\Http\Controllers\Api\TransaksiController::class);

Route::apiResource('/jurnal', App\Http\Controllers\Api\JurnalController::class);
Route::apiResource('/tipe_jurnal', App\Http\Controllers\Api\TipeJurnalController::class);

Route::apiResource('/role', App\Http\Controllers\Api\RoleController::class);
Route::apiResource('/profil', App\Http\Controllers\Api\ProfilController::class);
Route::get('/jurnal/data-akun/{id_data_akun}', [JurnalController::class, 'showId']);
Route::get('/jurnal/data-akun/{id_data_akun}/between-date', [JurnalController::class, 'showByIdAndDate']);


Route::post('/login', [ProfilController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [ProfilController::class, 'logout']);