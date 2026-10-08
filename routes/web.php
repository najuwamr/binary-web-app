<?php

use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\BulletinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('beranda');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/berita', [NewsController::class, 'index'])->name('berita.index');
        Route::get('/berita/create', [NewsController::class, 'create'])->name('berita.create');

        Route::get('/buletin', [BulletinController::class, 'index'])->name('buletin.index');
        
        Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip.index');
    });
