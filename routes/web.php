<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserProfileController;
use App\Models\Item;

Route::post('/inventory/confirm', [InventoryController::class, 'confirm'])->name('inventory.confirm');

Route::get('/inventory/qrcode/{data}', [InventoryController::class, 'generateQRCode'])
    ->name('inventory.qrcode')
    ->where('data', '.*'); // Allow any characters in the data parameter

Route::get('/scanner', [InventoryController::class, 'scanner'])->middleware(['auth', 'verified', 'role:Admin'])->name('scanner');

Route::post('/scanner/update', [InventoryController::class, 'updateCheckedItems'])->middleware(['auth', 'verified', 'role:Admin'])->name('scanner.update');
Route::post('/scanner/manual-qr', [InventoryController::class, 'handleManualQrInput'])->middleware(['auth', 'verified', 'role:Admin'])->name('scanner.manual-qr');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/', function () {
    return view('welcome');
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

Route::get('/inventory/create', [InventoryController::class, 'create'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.create');

Route::post('/inventory', [InventoryController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.store');

Route::delete('/inventory/items/{item}', [InventoryController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.destroy');

    Route::get('/inventory/items/{item}/edit', [InventoryController::class, 'edit'])
        ->middleware(['auth', 'verified', 'role:Admin'])
        ->name('inventory.edit');

    Route::get('/inventory/items/{item}', [InventoryController::class, 'show'])
        ->middleware(['auth', 'verified', 'role:Admin'])
        ->name('inventory.items.show');

    Route::put('/inventory/items/{item}', [InventoryController::class, 'update'])
        ->middleware(['auth', 'verified', 'role:Admin'])
        ->name('inventory.update');

Route::get('/inventory/available-items', [InventoryController::class, 'displayAvailableItems'])
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('inventory.available-items');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/qr/generate', [QRCodeController::class, 'showGenerator'])->name('qr.generate.form');
    Route::post('/qr/generate', [QRCodeController::class, 'generate'])->name('qr.generate');
    Route::get('/qr/scan', [QRCodeController::class, 'showScanner'])->name('qr.scan');
    Route::post('/qr/store-scan', [QRCodeController::class, 'storeScan'])->name('qr.storeScan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'index'])
        ->middleware(['auth', 'verified', 'role:Admin'])
        ->name('profile.index');
    Route::get('/profile/{id}', [UserProfileController::class, 'show'])
         ->name('profile.show')
         ->where('id', '[0-9]+');  // Ensure ID is numeric
    // Edit profile functionality removed - no edit route needed
    Route::post('/profile', [UserProfileController::class, 'store'])->name('profile.store');
    // Update route removed - edit profile functionality no longer available
    Route::delete('/profile/{id}', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/viewer/dashboard', [UserProfileController::class, 'viewerDashboard'])
        ->middleware('role:Viewer')
        ->name('viewer.dashboard');
    
    Route::post('/viewer/update-status', [UserProfileController::class, 'updateStatus'])
        ->middleware('role:Viewer')
        ->name('viewer.update-status');

    Route::get('/viewer/borrow', [UserProfileController::class, 'showBorrowForm'])
        ->middleware('role:Viewer')
        ->name('viewer.borrow');

    Route::post('/viewer/borrow', [UserProfileController::class, 'submitBorrowRequest'])
        ->middleware('role:Viewer')
        ->name('viewer.borrow.submit');

    Route::get('/viewer/borrow/history', [UserProfileController::class, 'borrowHistory'])
        ->middleware('role:Viewer')
        ->name('viewer.borrow.history');

    // Admin Borrow Request Management Routes
    Route::prefix('admin')->middleware(['auth', 'verified', 'role:Admin'])->group(function () {
        Route::get('/borrow-requests', [UserProfileController::class, 'adminBorrowRequests'])
            ->name('admin.borrow-requests');
        
        Route::get('/borrow-requests/pending', [UserProfileController::class, 'adminPendingRequests'])
            ->name('admin.borrow-requests.pending');
        
        Route::get('/borrow-requests/{id}', [UserProfileController::class, 'adminShowBorrowRequest'])
            ->name('admin.borrow-requests.show');
        
        Route::post('/borrow-requests/{id}/approve', [UserProfileController::class, 'adminApproveBorrowRequest'])
            ->name('admin.borrow-requests.approve');
        
        Route::post('/borrow-requests/{id}/reject', [UserProfileController::class, 'adminRejectBorrowRequest'])
            ->name('admin.borrow-requests.reject');
        
        Route::post('/borrow-requests/{id}/mark-returned', [UserProfileController::class, 'adminMarkReturned'])
            ->name('admin.borrow-requests.mark-returned');
    });
});

// Messages and Notifications routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/messages', function () {
        return view('messages.index');
    })->name('messages.index');
    
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');
});

// Route::get()
Route::get('/export/word/{roomId}', [InventoryController::class, 'exportWord'])->name('export.word');
require __DIR__.'/auth.php';