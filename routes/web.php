<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\CheckLastActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// Group routes that require authentication and email verification
Route::middleware(['auth', 'verified', CheckLastActivity::class])->group(function () {
    Route::get('/index', function () {
        return view('index');
    })->name('index');

    Route::get('/view_reports', function () {
        return view('view_reports');
    })->name('view_reports');

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

    Route::get('/unauthorized', function () {
        return view('unauthorized');
    })->name('unauthorized');

    Route::get('/reg_not_found', function () {
        return view('reg_not_found');
    })->name('reg_not_found');

    Route::get('/user_info', function () {
        return view('user_info');
    })->name('user_info');

    Route::get('/view_per_report', function () {
        return view('view_per_report');
    })->name('view_per_report');
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
