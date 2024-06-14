<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('kategori', KategoriController::class)->middleware('auth');
Route::resource('barang', BarangController::class)->middleware('auth');
Route::resource('barangmasuk', BarangMasukController::class)->middleware('auth');
Route::resource('barangkeluar', BarangKeluarController::class)->middleware('auth');

Route::get('login', [AuthController::class,'index'])->name('login')->middleware('guest');
Route::post('login', [AuthController::class,'authenticate']);
//route logout
Route::get('logout', [AuthController::class,'logout']);
Route::post('logout', [AuthController::class,'logout']);
//route register
Route::get('register', [AuthController::class,'create']);
Route::post('register', [AuthController::class,'register']);