<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AddPersonnelRequestController;
use App\Http\Controllers\EmployeeTrashController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    //Route::get('/data-management/personnel/individualRecords',[DataManagementController::class, 'viewIndividualRecords'])->name('data-management.personnel.view.individual.records');
    Route::get('/data-management/personnel-basic-information/download-template',[DataManagementController::class, 'downloadPersonnelBasicInformationTemplate'])->name('data-management.personnel-basic-information.download-template');
    Route::get('/data-management/personnel/{person}/edit',[DataManagementController::class, 'editPersonnel'])->name('data-management.personnel.edit');
    Route::put('/data-management/personnel/{person}',[DataManagementController::class, 'updatePersonnel'])->name('data-management.personnel.update');


    // Data Management -> Employment Status
    Route::get('/data-management/employment-status',[DataManagementController::class, 'employmentStatus'])->name('data-management.employment-status');
    Route::post('/data-management/employment-status/import',[DataManagementController::class, 'importEmploymentStatus'])->name('data-management.employment-status.import');
    Route::get('/data-management/employment-status/import/preview',[DataManagementController::class, 'employmentStatusImportPreview'])->name('data-management.employment-status.import.preview');
    Route::post('/data-management/employment-status/import/confirm',[DataManagementController::class, 'confirmEmploymentStatusImport'])->name('data-management.employment-status.import.confirm');
    Route::get('/data-management/employment-status/download-template',[DataManagementController::class, 'downloadEmploymentStatusTemplate'])->name('data-management.employment-status.download-template');
    Route::get('/data-management/employment-status/{employmentStatus}/edit',[DataManagementController::class, 'editEmploymentStatus'])->name('data-management.employment-status.edit');
    Route::put('/data-management/employment-status/{employmentStatus}',[DataManagementController::class, 'updateEmploymentStatus'])->name('data-management.employment-status.update');
    Route::get('/data-management/employment-status/{employmentStatus}/plantilla-search',[DataManagementController::class, 'searchEmploymentPlantilla'])->name('data-management.employment-status.plantilla-search');
    Route::get('/data-management/employment-status/{employmentStatus}/plantilla-assignments',[DataManagementController::class, 'employmentPlantillaAssignments'])->name('data-management.employment-status.plantilla-assignments');

});

Route::middleware(['auth', 'role:super_admin'])->group(function () {

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

});

// Data Management -> Medical Allowance Records
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/data-management/medical-allowance',[DataManagementController::class, 'medicalAllowance'])->name('data-management.medical-allowance');
    Route::post('/data-management/medical-allowance/import',[DataManagementController::class, 'importMedicalAllowance'])->name('data-management.medical-allowance.import');
    Route::get('/data-management/medical-allowance/import/preview',[DataManagementController::class, 'medicalAllowanceImportPreview'])->name('data-management.medical-allowance.import.preview');
    Route::post('/data-management/medical-allowance/import/confirm',[DataManagementController::class, 'confirmMedicalAllowanceImport'])->name('data-management.medical-allowance.import.confirm');
    Route::get('/data-management/medical-allowance/report',[DataManagementController::class, 'medicalAllowanceReport'])->name('data-management.medical-allowance.report');
    Route::get('/data-management/medical-allowance/template',[DataManagementController::class, 'downloadMedicalAllowanceTemplate'])->name('data-management.medical-allowance.template');
    Route::patch('/data-management/medical-allowance/{medicalAllowance}/availment',[DataManagementController::class, 'updateAvailment'])->name('medical-allowance.update-availment');

});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/data-management/medical-allowance/report/export',[DataManagementController::class, 'exportMedicalAllowanceReport'])->middleware('auth')->name('data-management.medical-allowance.report.export');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/data-management/medical-allowance/reports/{report}/validate',[DataManagementController::class, 'validateMedicalAllowance'])->name('data-management.medical-allowance.validate');
});

// Data Management -> Enrollment Records
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/data-management/enrollment',[DataManagementController::class, 'enrollment'])->name('data-management.enrollment');
    Route::post('/data-management/enrollment/import',[DataManagementController::class, 'importEnrollment'])->name('data-management.enrollment.import');
    Route::get('/data-management/enrollment/import/preview',[DataManagementController::class, 'enrollmentImportPreview'])->name('data-management.enrollment.import.preview');
    Route::post('/data-management/enrollment/import/confirm',[DataManagementController::class, 'confirmEnrollmentImport'])->name('data-management.enrollment.import.confirm');
    Route::get('/data-management/enrollment/download-template',[DataManagementController::class, 'downloadEnrollmentTemplate'])->name('data-management.enrollment.download-template');

});


// ============================================================
// SUPER ADMIN ONLY - Change User Role, Status and Reset Password
// ============================================================

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::patch('/data-management/personnel/{person}/access',[DataManagementController::class, 'updateUserAccess'])->name('data-management.personnel.update.access');


});


// ============================================================
// HR TRANSACRTIONS - PENDING FEATURES
// ============================================================

Route::view('/hr-transactions/service-records','errors.503',[],503)->name('hr-transactions.service-records');
Route::view('/hr-transactions/other-transactions','errors.503',[],503)->name('hr-transactions.other-transactions');


// ============================================================
// PAYROLL SERVICES
// ============================================================

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::view(
        '/payroll-services/payroll-inclusion',
        'payroll-services.payroll-inclusion'
    )->name('payroll-services.inclusion');
});

// ============================================================
// REPORT MANAGEMENT FEATURES
// ============================================================

Route::middleware(['auth', 'role:super_admin'])->prefix('data-management/reports')->group(function () {

        // REPORT LIST
        Route::get('/', [ReportController::class, 'index'])->name('data-management.reports');
        Route::post('/', [ReportController::class, 'store'])->name('reports.store');
        // SCHOOL SUBMISSIONS
        Route::get('/submissions', [ReportController::class, 'submissions'])->name('data-management.reports.submissions');
        Route::patch('/submissions/{submission}/submit', [ReportController::class, 'submit'])->name('reports.submit');
        Route::patch('/submissions/{submission}/verify', [ReportController::class, 'verify'])->name('reports.verify');
        // UPDATE OR CLOSE AN OVERALL REPORT
        Route::put('/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::patch('/{report}/close', [ReportController::class, 'close'])->name('reports.close');
        Route::patch('/submissions/{submission}/revert-validation',[ReportController::class, 'revertValidation'])->name('reports.revert-validation');
        Route::get('/submissions/export',[ReportController::class, 'exportSubmissions'])->name('reports.submissions.export');
        
});

// ============================================================
// ADD PERSONNEL - REQUEST FEATURES
// ============================================================

Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/data-management/add-personnel-requests',[AddPersonnelRequestController::class, 'index'])->name('add-personnel-requests.index');
    Route::get('/data-management/add-personnel-requests/create',[AddPersonnelRequestController::class, 'create'])->name('add-personnel-requests.create');
    Route::post('/data-management/add-personnel-requests',[AddPersonnelRequestController::class, 'store'])->name('add-personnel-requests.store');

});

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/admin/personnel-requests',[AddPersonnelRequestController::class, 'adminIndex'])->name('admin.personnel-requests.index');
    Route::get('/admin/personnel-requests/{personnelRequest}',[AddPersonnelRequestController::class, 'adminShow'])->name('admin.personnel-requests.show');
    Route::post('/admin/personnel-requests/{personnelRequest}/approve',[AddPersonnelRequestController::class, 'approve'])->name('admin.personnel-requests.approve');
    Route::post('/admin/personnel-requests/{personnelRequest}/disapprove',[AddPersonnelRequestController::class, 'disapprove'])->name('admin.personnel-requests.disapprove');

});

// ============================================================
// DANGER ZONE — EMPLOYEE DELETION AND TRASH BIN
// ============================================================

Route::middleware(['auth', 'role:super_admin,admin'])->prefix('danger-zone')->group(function () {

    // Both roles: view eligible employees and request history.
    Route::get('/delete-employee',[EmployeeTrashController::class, 'index'])->name('danger-zone.delete-employee');

    // Admin: request deletion.
    // Super admin: move directly to Trash Bin.
    Route::post('/employees/{employee}/deletion',[EmployeeTrashController::class, 'store'])->whereNumber('employee')->name('danger-zone.employees.delete');

    // Super admin only: review requests and restore employees.
    Route::middleware('role:super_admin')->group(function () {
        Route::post('/requests/{deletionRequest}/review',[EmployeeTrashController::class, 'review'])->whereNumber('deletionRequest')->name('danger-zone.requests.review');
        Route::post('/employees/{employee}/restore',[EmployeeTrashController::class, 'restore'])->whereNumber('employee')->name('danger-zone.employees.restore');

    });
});


require __DIR__.'/auth.php';
