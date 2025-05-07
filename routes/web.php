<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\InventoryController;

Route::get('/scanner', function () {
    return view('custodian/scanner');
 
 
})->middleware(['auth', 'verified'])->name('scanner');


Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/category', function () {
    return view('custodian/inventory/category');
})->middleware(['auth', 'verified'])->name('category');

Route::get('/dashboard', function () {
    return view('custodian/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/inventory/items', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.items');

Route::get('/inventory/create', function () {
    return view('custodian/inventory/create');
})->middleware(['auth', 'verified'])->name('inventory.create');

Route::post('/inventory/create', [InventoryController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.store');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/qr/generate', [QRCodeController::class, 'showGenerator'])->name('qr.generate.form');
    Route::post('/qr/generate', [QRCodeController::class, 'generate'])->name('qr.generate');
    Route::get('/qr/scan', [QRCodeController::class, 'showScanner'])->name('qr.scan');
    Route::post('/qr/store-scan', [QRCodeController::class, 'storeScan'])->name('qr.storeScan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';