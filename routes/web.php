<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// 1. Halaman Utama (Dashboard)
Route::get('/', [DashboardController::class, 'index']);

// 2. Halaman Edukasi
Route::get('/education', [DashboardController::class, 'education']);

// 3. Route Sistem (Untuk Auto-Fetch JavaScript)
Route::get('/system/live-fetch', [DashboardController::class, 'liveFetch']);

// 4. Route Test Koneksi (Opsional, buat debug)
Route::get('/test-connection', function () {
    return response()->json(['status' => 'Laravel OK', 'time' => now()]);
});