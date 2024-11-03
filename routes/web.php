<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UnauthorizedController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\ReportVehicleController;
use App\Http\Controllers\ViewReportsController;
use App\Http\Controllers\IndexReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use App\Http\Controllers\HeadViewViolationController;
use App\Http\Controllers\HeadViewUnauthorizedController;
use App\Http\Controllers\HeadViewSSUController;
use App\Http\Controllers\HeadReportsController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\SubUserLogsController;

use App\Http\Controllers\QRController;
use App\Http\Controllers\GateScannerController;
use App\Http\Controllers\HeadChangePassController;
use App\Http\Controllers\VisitorScannerController;

// Group routes that require authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // Route to view the index page using a controller
    Route::get('/index', [IndexReportController::class, 'index'])->name('index');

    // Keep this route definition
    Route::get('/view_reports', [ViewReportsController::class, 'index'])->name('view_reports');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route::get('/guidelines', function () {
    //     return view('guidelines');
    // })->name('guidelines');

    Route::get('/guidelines', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            return view('guidelines');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('head_guidelines');
        }
    })->name('guidelines');

    // Route::get('/myaccount', function () {
    //     return view('myaccount');
    // })->name('myaccount');

    Route::get('/myaccount', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            return view('myaccount');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('head_account');
        }
    })->name('myaccount');

    Route::get('/report_vehicle', function () {
        return view('report_vehicle');
    })->name('report_vehicle');

    Route::get('/report_vehicle', [ReportVehicleController::class, 'showForm'])->name('report.vehicle.form');
    Route::post('/report_vehicle', [ReportVehicleController::class, 'store'])->name('report.vehicle.store');
    
    // Route::get('/visitor_scanner', function () {
    //     return view('visitor_scanner');
    // })->name('visitor_scanner');

    Route::get('/visitor_scanner', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            return view('visitor_scanner');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('head_index');
        }
    })->name('visitor_scanner');
    
    Route::post('/visitor-scan-qr', [VisitorScannerController::class, 'scan'])->name('visitor-scan.qr');
    
    Route::get('/unauthorized', function () {
        $qrCode = session('qr', 'Unknown QR Code');
        return view('unauthorized', compact('qrCode'));
    })->name('unauthorized');

    Route::post('/store_unauthorized', [UnauthorizedController::class, 'store'])->name('store_unauthorized');
    
    Route::get('/visitorcode_notfound', function () {
        return view('visitorcode_notfound');
    })->name('visitorcode_notfound');

    Route::get('/reg_not_found', function () {
        return view('reg_not_found');
    })->name('reg_not_found');

    Route::get('/vehicle_registered_info', function () {
        return view('vehicle_registered_info');
    })->name('vehicle_registered_info');

    // Route::get('/gate_scanner', function () {
    //     return view('gate_scanner');
    // })->name('gate_scanner');

    Route::get('/gate_scanner', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            return view('gate_scanner');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('head_index');
        }
    })->name('gate_scanner');
    
    Route::post('/scan-qr', [GateScannerController::class, 'scanQR'])->name('gate_scanner.scan');

    // Route to display scanned QR code page
    // Route::get('/scanned_qr', function () {
    //     return view('scanned_qr');
    // })->name('scanned_qr');

    Route::get('/scanned_qr', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            return view('scanned_qr');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('head_index');
        }
    })->name('scanned_qr');

    // Route to display scanned QR code result
    Route::get('/scanned_result', [QRController::class, 'showResult'])->name('scanned.result');

    // Route to handle QR code scanning
    Route::post('/scan/qr', [QRController::class, 'scanQR'])->name('scan.qr');

    // Route to display specific vehicle information
    Route::get('/vehicle-info/{registration_no}', [QRController::class, 'showVehicleInfo'])->name('vehicle.info');


    Route::get('/view_per_report', function () {
        return view('view_per_report');
    })->name('view_per_report');

    // routes/web.php
    Route::get('/violation/{id}', [ViolationController::class, 'show'])->name('violation.show');
    // In routes/web.php
    Route::get('/violations', [ViolationController::class, 'index'])->name('violations.index');

    Route::get('/head_index', [HeadReportsController::class, 'index'])->name('head_index');

    // Route to view the violation list
    Route::get('/violation_list', [HeadViewViolationController::class, 'index'])->name('violation_list');

    Route::get('/sub-violation/{id}', [HeadViewViolationController::class, 'showSubViolationList'])->name('subViolationList');

    // Route to export current page CSV
    Route::get('/export-violation-csv', [HeadViewViolationController::class, 'exportViolationCsv'])->name('exportViolationCsv');

    // Route to export all records CSV
    Route::get('/export-all-violation-csv', [HeadViewViolationController::class, 'exportAllViolationCsv'])->name('exportAllViolationCsv');

    Route::get('/SubUserLogs/{vehicleOwnerId}', [SubUserLogsController::class, 'show'])->name('SubUserLogs');

    // Route to view the unauthorized list
    Route::get('/unauthorized_list', [HeadViewUnauthorizedController::class, 'index'])->name('unauthorized_list');
    // Route to export current page CSV
    Route::get('/export/unauthorized/csv', [HeadViewUnauthorizedController::class, 'exportCsv'])->name('exportUnauthorizedCsv');
    // Route to export all records CSV
    Route::get('/export/unauthorized/all', [HeadViewUnauthorizedController::class, 'exportAllUnauthorizedCsv'])->name('exportAllUnauthorizedCsv');

    Route::get('/head_userlogs', [UserLogController::class, 'index'])->name('head_userlogs');
    Route::get('/userlogs/export', [UserLogController::class, 'exportCsv'])->name('userlogs.export');
    Route::get('/userlogs/export-all', [UserLogController::class, 'exportAllCsv'])->name('userlogs.export.all');

    Route::get('/head_account', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            return view('SSUHead.head_account');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    })->name('head_account');

    Route::get('/head_guidelines', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            return view('SSUHead.head_guidelines');
        } else {
            // abort(403, 'Unauthorized action.');
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    })->name('head_guidelines');
    
    Route::get('/ssu_personnel', [HeadViewSSUController::class, 'index'])->name('ssu_personnel');
    // Route to handle form submission to add a new user
    Route::post('/ssu_personnel', [HeadViewSSUController::class, 'store'])->name('ssu_personnel');
});

// Routes that do not require authentication
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login'); // Login form
Route::post('/', [LoginController::class, 'login']); // Handle login

Route::get('/forgot_pass', [ForgotPasswordController::class, 'showForm'])->name('password.request'); // Forgot password form
Route::post('/forgot_pass', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email'); // Send reset code

Route::get('/pass_emailed', function (Request $request) {
    if (!$request->session()->has('reset_authorized')) {
        return redirect()->route('password.request');
    }

    // Clear the session variable after accessing the route
    $request->session()->forget('reset_authorized');
    
    return view('pass_emailed');
})->name('pass_emailed');

// Routes for password reset
Route::get('/reset_new_pass/{emp_no}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('reset_new_pass_with_emp_no');
Route::get('/reset_new_pass', [ResetPasswordController::class, 'showResetPasswordForm'])->name('reset_new_pass'); // URL without emp_no
Route::post('/update_password', [ResetPasswordController::class, 'updatePassword'])->name('update_password');

Route::get('/updated_pass_result', function () {
    // Check if the password update session variable is set
    if (!Session::has('password_updated')) {
        return redirect()->route('password.request');
    }

    // Clear the session variable after accessing the route
    Session::forget('password_updated');
    
    return view('updated_pass_result');
})->name('updated_pass_result'); // Password updated confirmation

Route::post('/change-password', [HeadChangePassController::class, 'changePassword'])->name('change.password');

Route::get('/email', function () {
    return view('email');
})->name('email'); // Password reset link emailed confirmation

