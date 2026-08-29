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

Route::middleware(['auth', 'role:super_admin,admin,user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');

});

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    // Data Management
    Route::get('/data-management', [DataManagementController::class, 'index'])->name('data-management');
    
    // Data Management -> Personnel Information
    Route::get('/data-management/personnel', [DataManagementController::class, 'personnel'])->name('data-management.personnel');
    Route::post('/data-management/personnel/import', [DataManagementController::class,'importPersonnel'])->name('data-management.personnel.import');
    Route::get('/data-management/personnel/import/preview',[DataManagementController::class, 'personnelImportPreview'])->name('data-management.personnel.import.preview');
    Route::post('/data-management/personnel/import/confirm', [DataManagementController::class,'confirmPersonnelImport'])->name('data-management.personnel.import.confirm');
    Route::get('/data-management/personnel/individualRecords',[DataManagementController::class, 'viewIndividualRecords'])->name('data-management.personnel.view.individual.records');
    Route::get('/data-management/personnel-basic-information/download-template',[DataManagementController::class, 'downloadPersonnelBasicInformationTemplate'])->name('data-management.personnel-basic-information.download-template');

    // Data Management -> Employment Status
    Route::get('/data-management/employment-status',[DataManagementController::class, 'employmentStatus'])->name('data-management.employment-status');
    Route::post('/data-management/employment-status/import',[DataManagementController::class, 'importEmploymentStatus'])->name('data-management.employment-status.import');
    Route::get('/data-management/employment-status/import/preview',[DataManagementController::class, 'employmentStatusImportPreview'])->name('data-management.employment-status.import.preview');
    Route::post('/data-management/employment-status/import/confirm',[DataManagementController::class, 'confirmEmploymentStatusImport'])->name('data-management.employment-status.import.confirm');
    Route::get('/data-management/employment-status/download-template',[DataManagementController::class, 'downloadEmploymentStatusTemplate'])->name('data-management.employment-status.download-template');

    // Data Management -> Plantilla Position Records
    Route::get('/data-management/plantilla',[DataManagementController::class, 'plantilla'])->name('data-management.plantilla');
    Route::post('/data-management/plantilla/import',[DataManagementController::class, 'importPlantilla'])->name('data-management.plantilla.import');
    Route::get('/data-management/plantilla/import/preview',[DataManagementController::class, 'plantillaImportPreview'])->name('data-management.plantilla.import.preview');
    Route::post('/data-management/plantilla/import/confirm',[DataManagementController::class, 'confirmPlantillaImport'])->name('data-management.plantilla.import.confirm');
    Route::get('/data-management/plantilla-database/download-template',[DataManagementController::class, 'downloadPlantillaDatabaseTemplate'])->name('data-management.plantilla-database.download-template');

    // Data Management -> School Database Records
    Route::get('/data-management/schools',[DataManagementController::class, 'schools'])->name('data-management.schools');
    Route::post('/data-management/schools/import',[DataManagementController::class, 'importSchools'])->name('data-management.schools.import');
    Route::get('/data-management/schools/import/preview',[DataManagementController::class, 'schoolImportPreview'])->name('data-management.schools.import.preview');
    Route::post('/data-management/schools/import/confirm',[DataManagementController::class, 'confirmSchoolImport'])->name('data-management.schools.import.confirm');
    Route::get('/data-management/school-database/download-template',[DataManagementController::class, 'downloadSchoolDatabaseTemplate'])->name('data-management.school-database.download-template');

    // Data Management -> Medical Allowance Records
    Route::get('/data-management/medical-allowance',[DataManagementController::class, 'medicalAllowance'])->name('data-management.medical-allowance');
    Route::post('/data-management/medical-allowance/import',[DataManagementController::class, 'importMedicalAllowance'])->name('data-management.medical-allowance.import');
    Route::get('/data-management/medical-allowance/import/preview',[DataManagementController::class, 'medicalAllowanceImportPreview'])->name('data-management.medical-allowance.import.preview');
    Route::post('/data-management/medical-allowance/import/confirm',[DataManagementController::class, 'confirmMedicalAllowanceImport'])->name('data-management.medical-allowance.import.confirm');
    Route::get('/data-management/medical-allowance/report',[DataManagementController::class, 'medicalAllowanceReport'])->name('data-management.medical-allowance.report');
    Route::get('/data-management/medical-allowance/template',[DataManagementController::class, 'downloadMedicalAllowanceTemplate'])->name('data-management.medical-allowance.template');


    // Data Management -> Enrollment Records
    Route::get('/data-management/enrollment',[DataManagementController::class, 'enrollment'])->name('data-management.enrollment');
    Route::post('/data-management/enrollment/import',[DataManagementController::class, 'importEnrollment'])->name('data-management.enrollment.import');
    Route::get('/data-management/enrollment/import/preview',[DataManagementController::class, 'enrollmentImportPreview'])->name('data-management.enrollment.import.preview');
    Route::post('/data-management/enrollment/import/confirm',[DataManagementController::class, 'confirmEnrollmentImport'])->name('data-management.enrollment.import.confirm');
    Route::get('/data-management/enrollment/download-template',[DataManagementController::class, 'downloadEnrollmentTemplate'])->name('data-management.enrollment.download-template');

});

// ============================================================
// SUPER ADMIN ONLY - Change User Role
// ============================================================

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::patch('/data-management/personnel/{person}/access',[DataManagementController::class, 'updateUserAccess'])->name('data-management.personnel.update.access');

});



require __DIR__.'/auth.php';
