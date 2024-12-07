<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('forgot_pass');
    }

    public function sendResetCode(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'emp_no' => 'required|exists:employee,emp_no',
        ]);

        if ($validator->fails()) {
            return redirect()->route('password.request')
                            ->withErrors($validator)
                            ->withInput();
        }

        // Fetch employee details using Query Builder
        $employee = DB::table('employee')->where('emp_no', $request->emp_no)->first();

        // Check if employee exists
        if (!$employee) {
            return redirect()->route('password.request')
                            ->with('error', 'Employee not found.')
                            ->withInput();
        }
        
        // Fetch associated authorized_user record using emp_id
        $authorizedUser = DB::table('authorized_user')->where('emp_id', $employee->id)->first();

        // Check if authorized_user exists
        if (!$authorizedUser) {
            return redirect()->route('password.request')
                            ->with('error', 'User information not found for this employee.')
                            ->withInput();
        }

        // Fetch associated users record using authorized_user_id
        $user = DB::table('users')->where('authorized_user_id', $authorizedUser->id)->first();

        // Check if users exists
        if (!$user) {
            return redirect()->route('password.request')
                            ->with('error', 'Login information not found for this user.')
                            ->withInput();
        }

        // Check if user_type is allowed to reset the password
        if ($authorizedUser->user_type != 2 && $authorizedUser->user_type != 3) {
            return redirect()->route('password.request')
                            ->with('error', 'You are not authorized to reset the password on this site, maybe you are on the wrong site.')
                            ->withInput();
        }

        // Check if there is an existing reset request
        $existingReset = DB::table('password_resets')
                        ->where('emp_no', $request->emp_no)
                        ->where('expiration', '>', now())
                        ->where('used_reset_token', 0)
                        ->first();

        if ($existingReset) {
            // Notify user that reset URL is still valid
            return redirect()->route('password.request')
                            ->with('error', 'Your requested URL hasn\'t expired yet, you can still use it to reset your password.')
                            ->withInput();
        }

        // Generate a unique reset token
        $resetToken = Str::random(60);

        // Store the new token in the password_resets table
        DB::table('password_resets')->insert([
            'emp_no' => $request->emp_no,
            'users_id' => $user->id,
            'reset_token' => $resetToken,
            'expiration' => now()->addMinutes(10), // Set expiration to 10 minutes
            'used_reset_token' => 0
        ]);

        // Generate reset link using the named route with emp_no parameter
        $resetLink = route('reset_new_pass', ['emp_no' => $request->emp_no, 't' => urlencode($resetToken)]);

        // Send reset link via email using Laravel's Mail facade
        try {
            // Send the email using the PasswordResetMail Mailable
            Mail::to($user->email)->send(new PasswordResetMail($authorizedUser, $resetLink));

            // Set session variable to authorize access to reset_code_pass.php
            session(['reset_authorized' => true]);

            $success = "Reset link sent to your email.";
            // Redirect user to pass_emailed.blade.php
            return redirect()->route('pass_emailed')->with('success', $success);
        } catch (\Exception $e) {
            return redirect()->route('password.request')
                            ->with('error', "Failed to send reset link. Error: {$e->getMessage()}")
                            ->withInput();
        }
    }
}
