<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(auth()->check()) {
        $role = strtolower(auth()->user()->role);
        // Sale -> POS
        if($role === 'sale') {
            return redirect()->route('pos.index');
        }
        // Admin/Stock -> Dashboard
        return redirect()->route('dashboard');
    }
    return redirect('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function() {
        $user = auth()->user();
        $role = strtolower($user->role);

        // Redirect Sale to POS
        if ($role === 'sale') {
            return redirect()->route('pos.index');
        }

        // Allow Admin & Stock on Dashboard
        if ($role === 'admin' || $role === 'stock') {
            return app(DashboardController::class)->index();
        }

        abort(403);
    })->name('dashboard');

    Route::middleware('role:admin,sale,stock')->group(function() {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
        Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    });

    Route::middleware('role:admin')->group(function() {
        Route::get('/reports/sales', [ReportController::class, 'index'])->name('reports.sales');
        Route::get('/reports/export', [ReportController::class, 'exportPdf'])->name('reports.export');
        Route::resource('staff', StaffController::class);
        Route::patch('/staff/{id}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset_password');
        Route::get('/history', [HistoryController::class, 'index'])->name('admin.history');
        Route::get('/attendance/export', [AttendanceController::class, 'exportPdf'])->name('attendance.export');
    });

    // --- POS ACCESS (UPDATED: REMOVED 'STOCK') ---
    // Only Admin and Sale can access POS. Stock users will get 403 Forbidden.
    Route::middleware('role:admin,sale')->group(function() {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
        Route::post('/pos/remote-push', [PosController::class, 'pushRemoteScan'])->name('pos.remote_push');
        Route::get('/pos/remote-poll', [PosController::class, 'pollRemoteScans'])->name('pos.remote_poll');
        Route::post('/pos/mobile-command-push', [PosController::class, 'pushToMobile'])->name('pos.mob_push');
        Route::get('/pos/mobile-command-poll', [PosController::class, 'pollFromMobile'])->name('pos.mob_poll');
        Route::post('/pos/generate-khqr', [PosController::class, 'generateKhqr'])->name('pos.khqr');
        Route::post('/pos/check-transaction', [PosController::class, 'checkTransaction'])->name('pos.check_transaction');
        Route::resource('customers', CustomerController::class)->only(['index', 'store']); 
    });

    // --- STOCK ACCESS ---
    Route::middleware('role:admin,stock')->group(function() {
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    });

});

require __DIR__.'/auth.php';