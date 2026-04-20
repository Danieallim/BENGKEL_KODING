<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController; 
use App\Http\Controllers\Admin\ObatController; // Tambahan untuk Obat
use App\Http\Controllers\Dokter\JadwalPeriksaController; // Pastikan import ini ada
use Illuminate\Support\Facades\Route;

// Arahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //  ================= POLI =================
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class); 
    Route::resource('obat', ObatController::class); 
});

// ================= DOKTER =================
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');
    
    // JadwalPeriksa
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
});

// ================= PASIEN =================
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');
});