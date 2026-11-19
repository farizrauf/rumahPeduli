<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PantiPortalController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/panti', [HomeController::class, 'pantiIndex'])->name('panti.index');
Route::get('/panti/{panti}', [HomeController::class, 'pantiShow'])->name('panti.show');
Route::get('/kampanye', [HomeController::class, 'campaignIndex'])->name('campaigns.index');
Route::get('/kampanye/{kampanye}', [HomeController::class, 'campaignShow'])->name('campaigns.show');
Route::get('/transparansi', [ReportController::class, 'index'])->name('reports.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:donatur'])->prefix('donatur')->name('donatur.')->group(function () {
    Route::get('/dashboard', [DonaturController::class, 'dashboard'])->name('dashboard');
    Route::get('/donasi', [DonaturController::class, 'donations'])->name('donations');
    Route::get('/donasi/{donasi}/bukti', [DonationController::class, 'receipt'])->name('donations.receipt');
    Route::get('/kampanye/{kampanye}/donasi', [DonationController::class, 'create'])->name('donate');
    Route::post('/kampanye/{kampanye}/donasi', [DonationController::class, 'store'])->name('donate.store');
});

Route::middleware(['auth', 'role:panti'])->prefix('pengelola')->name('panti.')->group(function () {
    Route::get('/dashboard', [PantiPortalController::class, 'dashboard'])->name('dashboard');
    Route::put('/profil', [PantiPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/{type}', [PantiPortalController::class, 'items'])->name('items');
    Route::post('/{type}', [PantiPortalController::class, 'storeItem'])->name('items.store');
    Route::put('/{type}/{id}', [PantiPortalController::class, 'storeItem'])->whereNumber('id')->name('items.update');
    Route::delete('/{type}/{id}', [PantiPortalController::class, 'destroyItem'])->whereNumber('id')->name('items.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/panti', [AdminController::class, 'panti'])->name('panti');
    Route::post('/panti/{panti}/verifikasi', [AdminController::class, 'verifyPanti'])->name('panti.verify');
    Route::delete('/panti/{panti}', [AdminController::class, 'destroyPanti'])->name('panti.destroy');
    Route::get('/kampanye', [AdminController::class, 'campaigns'])->name('campaigns');
    Route::get('/donasi', [AdminController::class, 'donations'])->name('donations');
    Route::post('/donasi/{donasi}/verifikasi', [AdminController::class, 'verifyDonation'])->name('donations.verify');
    Route::get('/pengeluaran', [AdminController::class, 'expenses'])->name('expenses');
    Route::post('/pengeluaran/{pengeluaran}/verifikasi', [AdminController::class, 'verifyExpense'])->name('expenses.verify');
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/pengguna/{user}/status', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::get('/laporan', [AdminController::class, 'reports'])->name('reports');
    Route::get('/audit', [AdminController::class, 'audit'])->name('audit');
});
