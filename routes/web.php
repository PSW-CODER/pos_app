<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderRequestController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/home', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('user.home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('product.list');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('store.product');
    Route::post('/admin/products/{id}/update', [ProductController::class, 'update'])->name('update.product');
    Route::post('/admin/products/{id}/delete', [ProductController::class, 'delete'])->name('delete.product');
    Route::get('/admin/users', [UserController::class, 'index'])->name('user.list');
    Route::post('/admin/users/{id}/update', [UserController::class, 'update'])->name('update.user');
    Route::post('/admin/users/{id}/delete', [UserController::class, 'delete'])->name('delete.user');
    Route::get('/admin/orders', [OrderRequestController::class, 'index'])->name('order.list');
});

require __DIR__.'/auth.php';
