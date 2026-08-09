<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataManagementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Data Management
    Route::get('/data-management', [DataManagementController::class, 'index'])->name('data-management');
    Route::get('/data-management/personnel', [DataManagementController::class, 'personnel'])->name('data-management.personnel');
    Route::get('/data-management/employment-status',[DataManagementController::class, 'employmentStatus'])->name('data-management.employment-status');
    Route::get('/data-management/plantilla',[DataManagementController::class, 'plantilla'])->name('data-management.plantilla');
    Route::get('/data-management/schools',[DataManagementController::class, 'schools'])->name('data-management.schools');
    Route::get('/data-management/medical-allowance',[DataManagementController::class, 'medicalAllowance'])->name('data-management.medical-allowance');
    Route::get('/data-management/enrollment',[DataManagementController::class, 'enrollment'])->name('data-management.enrollment');

});



require __DIR__.'/auth.php';
