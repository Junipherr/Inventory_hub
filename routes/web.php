<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::post('/inventory/confirm', [InventoryController::class, 'confirm'])->name('inventory.confirm');

use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ProfileController;
use App\Models\Item;

// Route::get('/debug-item-units', function () {
//     $items = Item::with('units')->get();

//     $result = $items->map(function ($item) {
//         return [
//             'item_id' => $item->id,
//             'item_name' => $item->item_name,
//             'units' => $item->units->map(function ($unit) {
//                 return [
//                     'unit_id' => $unit->id,
//                     'unit_number' => $unit->unit_number,
//                     'last_checked_at' => $unit->last_checked_at,
//                 ];
//             }),
//         ];
//     });

//     return response()->json($result);
// });

Route::get('/inventory/qrcode/{id}', [InventoryController::class, 'generateQRCode'])->name('inventory.qrcode');

Route::get('/scanner', [InventoryController::class, 'scanner'])->middleware(['auth', 'verified'])->name('scanner');

Route::post('/scanner/update', [InventoryController::class, 'updateCheckedItems'])->middleware(['auth', 'verified'])->name('scanner.update');

Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/category', function () {
    return view('custodian/inventory/category');
})->middleware(['auth', 'verified'])->name('category');

Route::get('/dashboard', [InventoryController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/items/{categoryId}', [InventoryController::class, 'getItemsByCategory'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.items.byCategory');

Route::get('/inventory/items', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.items');

Route::get('/inventory/create', function () {
    return view('custodian/inventory/create');
})->middleware(['auth', 'verified'])->name('inventory.create');

Route::post('/inventory/create', [InventoryController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.store');

Route::delete('/inventory/items/{item}', [InventoryController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.destroy');

Route::get('/inventory/items/{item}/edit', [InventoryController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.edit');

Route::put('/inventory/items/{item}', [InventoryController::class, 'update'])
    ->middleware(['auth', 'verified'])
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

    Route::get('/viewer/dashboard', function () {
        return view('viewer.dashboard');
    })->name('viewer.dashboard');
});

// Route::get()
require __DIR__.'/auth.php';
