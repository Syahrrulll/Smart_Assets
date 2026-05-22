<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return redirect()->route('items.index');
});

// --- AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PINTASAN SCAN QR RUANGAN (Bisa diakses tanpa login jika diinginkan, tapi kita arahkan ke login jika butuh auth) ---
Route::get('/ruangan/{lokasi}', function ($lokasi) {
    return redirect()->route('items.index', ['lokasi' => $lokasi]);
});

Route::middleware(['auth'])->group(function () {

    // --- SEMUA PENGGUNA LOGIN (ADMIN & STAF) ---
    
    // Barang (Staf hanya bisa lihat index/edit sesuai logika di controller)
    Route::get('items/download-labels-pdf', [ItemController::class, 'downloadLabelsPdf'])->name('items.download_pdf');
    Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
    Route::get('items/import', [ItemController::class, 'importForm'])->name('items.import.form');
    Route::post('items/import', [ItemController::class, 'import'])->name('items.import');
    Route::resource('items', ItemController::class);

    // Siklus Hidup Aset (Staf hanya bisa index, create, store, show)
    Route::resource('borrowings', \App\Http\Controllers\BorrowingController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('maintenance-tickets', \App\Http\Controllers\MaintenanceTicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('disposals', \App\Http\Controllers\DisposalController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('relocations', \App\Http\Controllers\RelocationController::class)->only(['index', 'create', 'store', 'show']);

    // Dashboard Staf
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

    // Pelaporan Staf
    Route::get('/reports/create', [\App\Http\Controllers\ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');

    // --- KHUSUS ADMINISTRATOR ---
    Route::middleware(['admin'])->group(function () {
        // Lokasi
        Route::resource('locations', LocationController::class)->only(['index', 'store', 'destroy']);
        
        // Hapus Massal Barang
        Route::post('/items/bulk-delete', [ItemController::class, 'bulkDelete'])->name('items.bulkDelete');
    
        Route::get('/backup/download', function () {
            if (Auth::user()->role !== 'admin') abort(403);
            
            $backupDir = storage_path('app/private/Laravel');
            if (!File::exists($backupDir)) {
                return back()->with('error', 'Belum ada file backup yang tersedia. Jalankan php artisan backup:run terlebih dahulu.');
            }
    
            $files = File::files($backupDir);
            if (count($files) == 0) {
                return back()->with('error', 'Belum ada file backup yang tersedia.');
            }
    
            // Ambil file terbaru
            usort($files, function($a, $b) {
                return $b->getMTime() - $a->getMTime();
            });
    
            return response()->download($files[0]->getPathname());
        })->name('backup.download');
        
        // Manajemen Pengguna
        Route::resource('users', UserController::class);

        // Siklus Hidup Aset (Admin Edit, Update, Destroy)
        Route::resource('borrowings', \App\Http\Controllers\BorrowingController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('maintenance-tickets', \App\Http\Controllers\MaintenanceTicketController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('disposals', \App\Http\Controllers\DisposalController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('relocations', \App\Http\Controllers\RelocationController::class)->only(['edit', 'update', 'destroy']);

        // Kotak Masuk Pelaporan
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::put('/reports/{report}', [\App\Http\Controllers\ReportController::class, 'update'])->name('reports.update');
    });
});
