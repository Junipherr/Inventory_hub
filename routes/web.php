<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::post('/inventory/confirm', [InventoryController::class, 'confirm'])->name('inventory.confirm');

use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ProfileController;
use App\Models\Item;

Route::get('/inventory/qrcode/{id}', [InventoryController::class, 'generateQRCode'])->name('inventory.qrcode');

Route::get('/scanner', [InventoryController::class, 'scanner'])->middleware(['auth', 'verified', 'role:Admin'])->name('scanner');

Route::post('/scanner/update', [InventoryController::class, 'updateCheckedItems'])->middleware(['auth', 'verified', 'role:Admin'])->name('scanner.update');

Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/category', function () {
    return view('custodian/inventory/category');
})->middleware(['auth', 'verified', 'role:Admin'])->name('category');

Route::get('/dashboard', [InventoryController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('dashboard');

Route::get('/dashboard/items/{categoryId}', [InventoryController::class, 'getItemsByCategory'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('dashboard.items.byCategory');

Route::get('/inventory/items', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.items');

Route::get('/inventory/create', function () {
    return view('custodian/inventory/create');
})->middleware(['auth', 'verified', 'role:Admin'])->name('inventory.create');

Route::post('/inventory/create', [InventoryController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.store');

Route::delete('/inventory/items/{item}', [InventoryController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.destroy');

Route::get('/inventory/items/{item}/edit', [InventoryController::class, 'edit'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.edit');

Route::put('/inventory/items/{item}', [InventoryController::class, 'update'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/qr/generate', [QRCodeController::class, 'showGenerator'])->name('qr.generate.form');
    Route::post('/qr/generate', [QRCodeController::class, 'generate'])->name('qr.generate');
    Route::get('/qr/scan', [QRCodeController::class, 'showScanner'])->name('qr.scan');
    Route::post('/qr/store-scan', [QRCodeController::class, 'storeScan'])->name('qr.storeScan');
});

use App\Http\Controllers\UserProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [UserProfileController::class, 'store'])->name('profile.store');
    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');

use App\Http\Controllers\UserProfileController;

Route::get('/viewer/dashboard', [UserProfileController::class, 'viewerDashboard'])
    ->middleware('role:Viewer')
    ->name('viewer.dashboard');
});

// Route::get()
require __DIR__.'/auth.php';
