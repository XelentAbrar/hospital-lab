<?php

use Illuminate\Support\Facades\Route;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\UnitController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\LabTestController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\PackageController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\LabReportController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\TestGroupController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\WidalTestController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\SubPackageController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\PatientTestController;
use XelentAbrar\HospitalLab\Http\Controllers\Lab\TestCategoryController;

Route::resource('units', UnitController::class)->names('units');
Route::resource('test-groups', TestGroupController::class)->names('test-groups');
Route::resource('packages', PackageController::class)->names('packages');
Route::resource('sub-packages', SubPackageController::class)->names('sub-packages');
Route::resource('test-categories', TestCategoryController::class)->names('test-categories');
Route::resource('lab-tests', LabTestController::class)->names('lab-tests');
Route::get('other-receipt', [LabTestController::class, 'OtherReceipt'])->name('lab-tests.other-receipt');
Route::get('out-door', [LabTestController::class, 'OutDoor'])->name('lab-tests.outdoor');
Route::resource('widal-tests', WidalTestController::class)->names('widal-tests');
Route::resource('patient-tests', PatientTestController::class)->names('patient-tests');
Route::get('other_receipt', [PatientTestController::class, 'other_receipt'])->name('patient-tests.other_receipt');

Route::get('patient-tests/print/{patient_test}', [PatientTestController::class, 'print'])->name('patient-tests.print');
Route::get('patient-tests/print/detail/{patient_test}', [PatientTestController::class, 'detailPrint'])->name('patient-tests.detail-print');
Route::get('/lab/test-count',[LabReportController::class, 'TestCountReport'])->name('lab.test-count');
Route::get('/lab/package-count',[LabReportController::class, 'TestCountByPackage'])->name('lab.package-count');
Route::get('/lab/referral-by',[LabReportController::class, 'TestReferralReport'])->name('lab.referral-by');
Route::get('lab/payment-report',[LabReportController::class, 'PaymentReport'])->name('lab.payment-report');
Route::get('lab/careoff-report',[LabReportController::class, 'LabCareOFFReport'])->name('lab.careoff-report');
Route::get('lab/critical-report',[LabReportController::class, 'CriticalPatientsReport'])->name('lab.critical-report');
Route::get('lab/report-lab-detail-summary',[LabReportController::class, 'SummaryByLabTestReport'])->name('lab.report-lab-detail-summary');
Route::get('lab/report-lab-detail',[LabReportController::class, 'LabTestDetailReport'])->name('lab.report-lab-detail');

Route::post('/patient-tests/cancel-receipt/{patient_test}', [PatientTestController::class,'cancelReceipt'])->name('patient-tests.cancelReceipt');
