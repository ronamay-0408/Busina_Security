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

// Group routes that require authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // Route to view the index page using a controller
    Route::get('/index', [IndexReportController::class, 'index'])->name('index');

    // Route::get('/view_reports', function () {
    //     return view('view_reports');
    // })->name('view_reports');

    // Keep this route definition
    Route::get('/view_reports', [ViewReportsController::class, 'index'])->name('view_reports');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/scanned_qr', function () {
        return view('scanned_qr');
    })->name('scanned_qr');

    Route::get('/guidelines', function () {
        return view('guidelines');
    })->name('guidelines');

    Route::get('/myaccount', function () {
        return view('myaccount');
    })->name('myaccount');

    Route::get('/report_vehicle', function () {
        return view('report_vehicle');
    })->name('report_vehicle');

    Route::get('/report_vehicle', [ReportVehicleController::class, 'showForm'])->name('report.vehicle.form');
    Route::post('/report_vehicle', [ReportVehicleController::class, 'store'])->name('report.vehicle.store');    

    Route::get('/unauthorized', function () {
        return view('unauthorized');
    })->name('unauthorized');
    Route::post('/store_unauthorized', [UnauthorizedController::class, 'store'])->name('store_unauthorized');

    Route::get('/reg_not_found', function () {
        return view('reg_not_found');
    })->name('reg_not_found');

    Route::get('/user_info', function () {
        return view('user_info');
    })->name('user_info');

    Route::get('/view_per_report', function () {
        return view('view_per_report');
    })->name('view_per_report');

    // routes/web.php
    Route::get('/violation/{id}', [ViolationController::class, 'show'])->name('violation.show');
    // In routes/web.php
    Route::get('/violations', [ViolationController::class, 'index'])->name('violations.index');


    Route::get('/head_index', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            return view('SSUHead.head_index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    })->name('head_index');

    // Route::get('/violation_list', function () {
    //     // Check user_type and redirect accordingly
    //     $user = Auth::user();
    //     if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
    //         return view('SSUHead.violation_list');
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // })->name('violation_list');

    Route::get('/violation_list', [HeadViewViolationController::class, 'index'])->name('violation_list');

    // Route::get('/unauthorized_list', function () {
    //     // Check user_type and redirect accordingly
    //     $user = Auth::user();
    //     if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
    //         return view('SSUHead.unauthorized_list');
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // })->name('unauthorized_list');

    // Route to view the unauthorized list
    Route::get('/unauthorized_list', [HeadViewUnauthorizedController::class, 'index'])->name('unauthorized_list');

    Route::get('/head_account', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            return view('SSUHead.head_account');
        } else {
            abort(403, 'Unauthorized action.');
        }
    })->name('head_account');

    Route::get('/head_guidelines', function () {
        // Check user_type and redirect accordingly
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            return view('SSUHead.head_guidelines');
        } else {
            abort(403, 'Unauthorized action.');
        }
    })->name('head_guidelines');

    // Route::get('/ssu_personnel', function () {
    //     // Check user_type and redirect accordingly
    //     $user = Auth::user();
    //     if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
    //         return view('SSUHead.ssu_personnel');
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // })->name('ssu_personnel');
    
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

Route::get('/email', function () {
    return view('email');
})->name('email'); // Password reset link emailed confirmation

