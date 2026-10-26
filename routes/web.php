<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public Landing Page & Tracking
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/order/submit', [PublicController::class, 'submitOrder'])->name('public.order.submit');
Route::get('/track/{code?}', [PublicController::class, 'track'])->name('public.track');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1. Owner Routes (Can edit prices, monitor all other 4 roles)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [OwnerController::class, 'index'])->name('dashboard');
    Route::get('/prices', [OwnerController::class, 'prices'])->name('prices');
    Route::post('/prices/{package}', [OwnerController::class, 'updatePrice'])->name('prices.update');
    Route::get('/monitoring', [OwnerController::class, 'monitoring'])->name('monitoring');
    Route::get('/orders', [OwnerController::class, 'orders'])->name('orders');
});

// 2. Admin Routes (View-only mirror of owner)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
    Route::get('/monitoring', [AdminController::class, 'monitoring'])->name('monitoring');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
});

// 3. Supir (Driver) Routes (Pickup, input item count, drop at laundry, delivery queue & complete)
Route::middleware(['auth', 'role:supir'])->prefix('supir')->name('supir.')->group(function () {
    Route::get('/', [DriverController::class, 'index'])->name('dashboard');
    Route::post('/order/create-pickup', [DriverController::class, 'createPickup'])->name('create-pickup');
    Route::post('/order/{order}/pickup', [DriverController::class, 'pickup'])->name('pickup');
    Route::post('/order/{order}/drop', [DriverController::class, 'drop'])->name('drop');
    Route::post('/order/{order}/start-delivery', [DriverController::class, 'startDelivery'])->name('start-delivery');
    Route::post('/order/{order}/deliver', [DriverController::class, 'deliver'])->name('deliver');
});

// 4. Operator Routes (Priority queue, recount with discrepancy detection, delay/resume, finish to delivery queue)
Route::middleware(['auth', 'role:oprator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('dashboard');
    Route::post('/order/{order}/start', [OperatorController::class, 'start'])->name('start');
    Route::post('/order/{order}/recount', [OperatorController::class, 'recount'])->name('recount');
    Route::post('/order/{order}/delay', [OperatorController::class, 'delay'])->name('delay');
    Route::post('/order/{order}/resume', [OperatorController::class, 'resume'])->name('resume');
    Route::post('/order/{order}/finish', [OperatorController::class, 'finish'])->name('finish');
});

// 5. Customer Routes (Package selection, priority rules, estimated completion tracking, order history)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('dashboard');
    Route::get('/order/new', [CustomerController::class, 'createOrder'])->name('order.create');
    Route::post('/order/new', [CustomerController::class, 'storeOrder'])->name('order.store');
    Route::get('/history', [CustomerController::class, 'history'])->name('history');
});
