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

        Route::post('/berita/{article}/archive', [NewsController::class, 'archive'])
            ->name('berita.archive');
        Route::resource('berita', NewsController::class)
            ->except(['destroy'])
            ->parameters(['berita' => 'article']);

        Route::resource('buletin', BulletinController::class)
            ->parameters(['buletin' => 'buletin']);

        Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip.index');
    });
