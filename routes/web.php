<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
    // return view('welcome');

    // return view('home');

// });


 Route::group(array('middleware' => 'auth'), function () {

Route::get('/', [HomeController::class, 'addEmployeeOtherSecondForm']);

Route::get('/get-vendor-data', [HomeController::class, 'getVendorData']);

Route::get('/get-employee-others-reports', [HomeController::class, 'getEmployeeOthersReports']);


// Route::get('/add-employee-others-second-form', [HomeController::class, 'addEmployeeOtherSecondForm']);

Route::get('/get-head-locations', [HomeController::class, 'getHeadLocations']);
Route::get('/get-heads', [HomeController::class, 'getHeads']);

Route::post('/insert-closing', [HomeController::class, 'insertClosing']);



Route::get('/get-closing-list', [HomeController::class, 'getClosingList']);
Route::get('/edit-closing', [HomeController::class, 'editClosing']);
Route::get('/delete-closing', [HomeController::class, 'deleteClosing']);

Route::get('/get-closing-pdf/{id?}', [HomeController::class, 'getClosingPdf']);

Route::get('/get-closing-view/{id?}', [HomeController::class, 'getClosingView']);

Route::get('/add-easypaisa-form', [HomeController::class, 'addEasypaisaForm']);

// Route::post('/insert-easypaisa-amount', [HomeController::class, 'insertEasypaisaAmount']);

Route::get('/easypaisa-amount-list', [HomeController::class, 'easypaisaAmountList']);

Route::get('/add-employee-others-form', [HomeController::class, 'addEmployeeOthersForm']);

Route::get('/add-employee-others-form', [HomeController::class, 'addEmployeeOthersForm']);

Route::get('/add-hbl-form', [HomeController::class, 'addEmployeeOthersForm']);

Route::post('/edit-hbl-amount', [HomeController::class, 'editHBLAmount']);


Route::post('/get-employees', [HomeController::class, 'getEmployees']);

Route::post('/insert-paid_amount', [HomeController::class, 'insertPaidAmount']);

Route::post('/insert-employee-others', [HomeController::class, 'insertEmployeeOthers']);

Route::get('/list-employee-others', [HomeController::class, 'listEmployeeOthers']);

Route::get('/choose-option-employee-other', [HomeController::class, 'chooseOptionEmployeeOther']);

Route::post('/get-current_amount', [HomeController::class, 'getCurrentAmount']);

Route::get('/employee-report', [HomeController::class, 'employeeReport']);

Route::get('/view-employee-report', [HomeController::class, 'ViewEmployeeReport']);

Route::get('/get-pdf-of-employee-others', [HomeController::class, 'getPdfOfEmployeeOthers']);

Route::get('/get-data-of-employee', [HomeController::class, 'getDataofEmployee']);

Route::get('/edit-employee-others', [HomeController::class, 'editEmployeeOthers']);



Route::get('/return-amount-form/{id}', [HomeController::class, 'returnAmountForm']);

Route::post('/insert-return-amount-easypaisa', [HomeController::class, 'insertReturnAmountEasypaisa']);

Route::get('/get-report-easypaisa-amount', [HomeController::class, 'getReportEasypaisaAmount']);

//viewReportEasypaisaAmount (this is old function)
Route::get('/view-report-easypaisa-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'easypaisaFullReportSecondView']);

//
Route::get('/daily-closing', [HomeController::class, 'dailyClosing']);

Route::get('/daily-closing-grand-report', [HomeController::class, 'dailyClosingGrandReport']);


Route::get('/add-sadqah', [HomeController::class, 'addSadqah']);

Route::get('/easypaisa-form', [HomeController::class, 'easypaisaForm']);

Route::post('/insert-easypaisa-amount', [HomeController::class, 'insertEasypaisaAmount']);

Route::get('/get-report-of-easypaisa-amount', [HomeController::class, 'getReportofEasypaisaAmount']);

Route::get('/get-full-report-of-easypaisa-amount', [HomeController::class, 'getFullReportofEasypaisaAmount']);

Route::get('/get-pdf-report-of-easypaisa-amount', [HomeController::class, 'getPdfReportOfEasypaisaAmount']);




Route::get('/get-full-report-of-closing-view', [HomeController::class, 'getFullReportofClosingView']);

Route::get('/get-full-report-of-closing', [HomeController::class, 'getFullReportofClosing']);

Route::get('/pending-form', [HomeController::class, 'pendingForm']);

Route::post('/insert-pending', [HomeController::class, 'insertPending']);

Route::get('/get-list-of-pending', [HomeController::class, 'getListOfPending']);

Route::get('/edit-pending-amount', [HomeController::class, 'editPendingAmount']);


//use in header
Route::get('/easypaisa-last-closing-amount', [HomeController::class, 'easypaisaLastClosingAmount']);

Route::get('/hbl-last-closing-amount', [HomeController::class, 'hblLastClosingAmount']);

Route::get('/delete-pending', [HomeController::class, 'deletePending']);

// Route::get('/pending-report-view', [HomeController::class, 'PendingReportView']);

Route::get('/generate-full-pending-report', [HomeController::class, 'generateFullPendingReport']);

Route::get('/get-pending-pdf', [HomeController::class, 'getPendingPdf']);


Route::get('/get-pending-full-list', [HomeController::class, 'getPendingFullList']);

Route::get('/pay-now/{amount}/{date}/{id}/{payid}', [HomeController::class, 'payNow']);

Route::post('/insert-pay-now', [HomeController::class, 'insertPayNow']);

Route::post('/get-employee-for-pending', [HomeController::class, 'getEmployeeForPending']);

Route::get('/hbl-form', [HomeController::class, 'HblForm']);

Route::post('/insert-hbl-amount', [HomeController::class, 'insertHblAmount']);


Route::get('/get-report-of-hbl-amount', [HomeController::class, 'getReportofHblAmount']);

Route::get('/get-full-report-of-hbl-amount', [HomeController::class, 'getFullReportofHblAmount']);

Route::get('/get-pdf-of-hbl-amount', [HomeController::class, 'getPdfReportofHblAmount']);

Route::get('/view-report-hbl-amount/{from_date}/{to_date}', [HomeController::class, 'viewReportHblAmount']);



Route::get('/salary-form', [HomeController::class, 'salaryForm']);

Route::get('/get-data-of-employee-salary', [HomeController::class, 'getDataofEmployeeSalary']);

Route::post('/delete-salary-record', [HomeController::class, 'deleteSalaryRecord']);


Route::get('/pay-now-salary/{id}/{date}/{salary}/{branch}/{name}/{employee_branch}/{joining}/{employee_post}', [HomeController::class, 'payNowSalary']);

Route::post('/check-advance-salary', [HomeController::class, 'checkAdvanceSalary']);

Route::post('/final-salary-insert', [HomeController::class, 'finalSalaryInsert']);

Route::get('/get-salary-report', [HomeController::class, 'getSalaryReport']);

Route::get('/get-salary-detail/{month}', [HomeController::class, 'getSalaryDetail']);

Route::get('/get-salary-upaid-detail/{month}', [HomeController::class, 'getSalaryUnpaidDetail']);


Route::get('/get-salary-report-view', [HomeController::class, 'getSalaryReportView']);

// Route::post('/get-salary-pdf', [HomeController::class, 'getSalaryPdf']);

Route::post('/get-salary-pdf', [HomeController::class, 'getSalaryPdf']);

Route::get('/get-paid-salary/{month}', [HomeController::class, 'getPaidSalary']);

Route::post('/edit-easypaisa-paid-amount', [HomeController::class, 'editEasypaisaPaidAmount']);

Route::get('/get-sadqa-report', [HomeController::class, 'getSadqaReport']);

Route::get('/locker', [HomeController::class, 'locker']);

Route::get('/edit-locker-amount', [HomeController::class, 'editLockerAmount']);


Route::get('/locker-amount', [HomeController::class, 'lockerAmount']);

Route::post('/edit-locker-amount-new', [HomeController::class, 'editLockerAmountNew']);

Route::get('/insert-locker-amount', [HomeController::class, 'insertlockerAmount']);

Route::get('/add-locker-amount-form', [HomeController::class, 'addLockerAmountForm']);

Route::post('/insert-lock-amount', [HomeController::class, 'insertLockAmount']);

Route::get('/get-locker-add-amount', [HomeController::class, 'getLockerAddAmount']);

Route::get('/locker-amount-list', [HomeController::class, 'lockerAmountList']);

Route::post('/edit-locker-amount-detail', [HomeController::class, 'editLockerAmountDetail']);

Route::get('/get-report-locker-amount', [HomeController::class, 'getReportLockerAmount']);

Route::get('/get-pdf-locker-amount', [HomeController::class, 'getPdfLockerAmount']);

Route::get('/get-view-locker-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewLockerAmount']);


Route::post('/insert-locker-paid-amount', [HomeController::class, 'insertLockerPaidAmount']);


Route::get('/get-locker-paid-amount-list', [HomeController::class, 'getLockerPaidAmountList']);

Route::get('/get-full-report-locker-amount', [HomeController::class, 'getFullReportLockerAmount']);

// Route::get('/get-full-report-locker-amount', [HomeController::class, 'getFullReportLockerAmount']);

Route::get('/get-pdf-report-locker', [HomeController::class, 'getPdfReportLocker']);

Route::get('/vendor-form', [HomeController::class, 'vendorForm']);

Route::post('/get-vendors', [HomeController::class, 'getVendors']);

Route::post('/insert-vendor', [HomeController::class, 'insertVendor']);

Route::post('/edit-vendor-detail', [HomeController::class, 'editVendorDetail']);

Route::post('/delete-vendor-detail', [HomeController::class, 'deleteVendorDetail']);

Route::get('/pay-vendor-amount', [HomeController::class, 'payVendorAmount']);

Route::post('/edit-pay-vendor-amount', [HomeController::class, 'editPayVendorAmount']);

Route::post('/get-vendor-total-amount', [HomeController::class, 'getVendorTotalAmount']);

Route::post('/insert-vendor-paid-amount', [HomeController::class, 'insertVendorPaidAmount']);

Route::get('/get-vendor-paid-amount-list', [HomeController::class, 'getVendorPaidAmountList']);


Route::get('/get-vendor-grand-list-with-full-detail', [HomeController::class, 'getVendorListWithFullDetail']);



Route::get('/pay-amount-report-vendor', [HomeController::class, 'payAmountReportVendor']);

Route::get('/pay-amount-report-vendor-pdf', [HomeController::class, 'payAmountReportVendorPdf']);

Route::get('/view-pay-amount-vendor-grand-total-report', [HomeController::class, 'ViewPayAmountVendorGrandTotalReport']);

//Route::get('/view-pay-amount-vendor-remaining-total-report/{from_date?}/{to_date?}', [HomeController::class, 'ViewPayAmountVendorRemainingTotalReport']);



Route::post('/check-pendings', [HomeController::class, 'checkPendings']);

Route::post('/check-riders-amounts', [HomeController::class, 'checkRiderAmounts']);

Route::get('/get-vendor-list', [HomeController::class, 'getVendorList']);

Route::get('/get-vendor-full-list', [HomeController::class, 'getVendorFullList']);

Route::get('/get-pending-list-view/{from_date?}/{to_date?}/{employee?}/{status?}', [HomeController::class, 'getPendingListView']);


Route::get('/pay-now-vendor/{amount}/{date}/{id}/{payid}', [HomeController::class, 'payNowVendor']);

Route::post('/insert-pay-now-vendor', [HomeController::class, 'insertPayNowVendor']);

Route::get('/get-report-vendor-pdf', [HomeController::class, 'getReportVendorPdf']);

Route::get('/get-vendors-grand-pdf-report/{from_date?}/{to_date?}/{vendor?}', [HomeController::class, 'getVendorsGrandPdfReport']);


Route::get('/ride-form', [HomeController::class, 'rideForm']);
Route::post('/insert-rides', [HomeController::class, 'insertRides']);
Route::post('/get-riders', [HomeController::class, 'getRiders']);

Route::get('/edit-rider-detail', [HomeController::class, 'editRiderDetail']);

Route::get('/get-list-riders', [HomeController::class, 'getListRiders']);

Route::get('/get-riders-list-view/{from_date}/{to_date}/{shift?}', [HomeController::class, 'getRidersListView']);

Route::get('/pay-now-rides/{amount}/{date}/{id}/{payid}', [HomeController::class, 'payNowRides']);

Route::get('/pay-now-bulk-rides/{bulk_array}', [HomeController::class, 'payNowBulkRides']);

Route::post('/insert-bulk-pay-now-rides', [HomeController::class, 'insertBulkPayNowRides']);


Route::post('/insert-pay-now-rides', [HomeController::class, 'insertPayNowRides']);

Route::get('/get-rides-full-list', [HomeController::class, 'getRidesFullList']);

Route::get('/get-rides-pdf', [HomeController::class, 'getRidesPdf']);

Route::get('/get-full-data-of-daily-closing', [HomeController::class, 'getFullDataofDailyClosing']);
Route::get('/get-pdf-data-of-daily-closing/{from_date?}/{to_date?}/{location?}/{head?}', [HomeController::class, 'getPdfDataofDailyClosing']);

Route::get('/get-view-of-daily-closing-grand-data/{from_date?}/{to_date?}/{head?}/{location?}', [HomeController::class, 'getViewofDailyClosingGrandData']);

Route::get('/check-balance', [HomeController::class, 'checkBalance']);


// Route::get('/get-paid-salary', [HomeController::class, 'getPaidSalary']);
// Route::get('/get-unpaid-salary', [HomeController::class, 'getUnpaidSalary']);

 Route::get('/test', [HomeController::class, 'test']);

 Route::get('/pay-sadqa-form', [HomeController::class, 'paySadqaForm']);

 Route::post('/pay-sadqa-insert', [HomeController::class, 'paySadqaInsert']);

 Route::get('/get-sadqa-list', [HomeController::class, 'getSadqaList']);

 Route::get('/view-sadqa-report', [HomeController::class, 'viewSadqaReport']);

 Route::get('/pay-installment', [HomeController::class, 'payInstallment']);
 
 Route::post('/pay-installment-insert', [HomeController::class, 'payInstallmentInsert']);

 Route::get('/get-installment-list', [HomeController::class, 'getInstallmentList']);

 Route::get('/edit-installment', [HomeController::class, 'editInstallment']);

 Route::get('/delete-installment', [HomeController::class, 'deleteInstallment']);

 Route::get('/get-installment-report/{from_date}/{to_date}', [HomeController::class, 'getInstallmentReport']);

 Route::get('/view-install-grand-report/{from_date}/{to_date}', [HomeController::class, 'viewInstallGrandReport']);

 Route::get('/owner-pending', [HomeController::class, 'ownerPending']);

 Route::post('/insert-owner-pending', [HomeController::class, 'insertOwnerPending']);

 Route::get('/get-owner-pending-list', [HomeController::class, 'getOwnerPendingList']);

 Route::get('/edit-owner-pending', [HomeController::class, 'editOwnerPending']);

 Route::get('/foodpanda-to-hbl', [HomeController::class, 'foodpandaToHbl']);
 
 Route::post('/insert-foodpanda-to-hbl', [HomeController::class, 'insertFoodpandaToHbl']);

 Route::get('/insert-foodpanda-to-hbl-list', [HomeController::class, 'insertFoodpandaToHblList']);

 Route::get('/edit-foodpanda-amount', [HomeController::class, 'editFoodpandaAmount']);

 Route::get('/view-chart', [HomeController::class, 'viewChart']);

 Route::get('/view-chart-branchwise', [HomeController::class, 'viewChartBrachWise']);

 Route::get('/view-chart-profit-loss', [HomeController::class, 'viewChartProfitLoss']);


 Route::get('/edit-pay-sadqa', [HomeController::class, 'editPaySadqa']);


 Route::get('/get-view-locker-amount-new-created/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewLockerAmountNewCreated']);

 Route::get('/get-view-locker-amount-new-created-pdf', [HomeController::class, 'getViewLockerAmountNewCreatedPdf']);

 //second view for calculation of each head

 Route::get('/get-view-locker-amount-new-created-second/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewLockerAmountNewCreatedSecond']);



 //Route::get('/get-view-locker-amount/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewLockerAmount']);


 //easypaisa

 Route::get('/easypaisa-full-report-second-view/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'easypaisaFullReportSecondView']);
 Route::get('/easypaisa-full-report-second-view-pdf', [HomeController::class, 'easypaisaFullReportSecondViewPdf']);
 //second view for calculation of each head
 Route::get('/get-view-easypaisa-amount-new-created-second/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewEasypaisaAmountNewCreatedSecond']);


 
 
 Route::get('/hbl-full-report-second-view/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'hblFullReportSecondView']);


 Route::get('/hbl-full-report-second-view-pdf', [HomeController::class, 'hblFullReportSecondViewPdf']);

 Route::get('/get-view-hbl-amount-new-created-second/{from_date}/{to_date}/{type?}/{employee_others?}', [HomeController::class, 'getViewHblAmountNewCreatedSecond']);



 Route::get('/delete-foodpanda-amount', [HomeController::class, 'deleteFoodpandaAmount']);

 Route::get('/view-owner-pending-report/{from?}/{to?}/{pending_type?}', [HomeController::class, 'viewOwnerPendingReport']);

 Route::post('/get-foodpanda-amount-using-date', [HomeController::class, 'getFoodpandaAmountUsingDate']);

 Route::get('/view-foodpanda-amounts/{from_date?}/{to_date?}', [HomeController::class, 'viewFoodpandaAmounts']);

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register', [App\Http\Controllers\HomeController::class, 'register'])->name('register');

Route::post('/register-user', [App\Http\Controllers\HomeController::class, 'registerUser'])->name('register-user');


Route::post('post-login', [HomeController::class, 'postLogin'])->name('login.post');

Route::get('logout', [HomeController::class, 'logout']);

});


Route::group(array('middleware' => 'guest'), function () {

    // Route::get('/register', [App\Http\Controllers\HomeController::class, 'register'])->name('register');
    // Route::post('/register-user', [App\Http\Controllers\HomeController::class, 'registerUser'])->name('register-user');

    Route::get('login', [HomeController::class, 'loginForm'])->name('login');
    Route::post('post-login', [HomeController::class, 'postLogin'])->name('login.post');
});

// Auth::routes();