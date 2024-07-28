<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm(Request $request)
    {
        // Retrieve emp_no and token from query string
        $emp_no = $request->query('emp_no');
        $token = $request->query('t');

        // Check if the token is valid and not used
        $password_reset = DB::table('password_resets')
            ->where('emp_no', $emp_no)
            ->where('reset_token', $token)
            ->where('used_reset_token', 0)
            ->where('expiration', '>', now())
            ->first();

        if (!$password_reset) {
            return redirect()->route('password.request')
                             ->with('error', 'Ooops... Your Reset Url is invalid or expired reset token.');
        }

        // Pass emp_no and token to the view
        return view('reset_new_pass', compact('emp_no', 'token'));
    }

    public function updatePassword(Request $request)
    {
        // Validate form data
        $request->validate([
            'emp_no' => 'required',
            'new_pass' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // Retrieve form data
        $emp_no = $request->input('emp_no');
        $new_password = $request->input('new_pass');
        $token = $request->input('token');

        // Fetch password reset record using emp_no and token
        $password_reset = DB::table('password_resets')
            ->where('emp_no', $emp_no)
            ->where('reset_token', $token)
            ->where('used_reset_token', 0)
            ->where('expiration', '>', now())
            ->first();

        // Check if password reset record exists
        if (!$password_reset) {
            return redirect()->route('password.request')
                             ->with('error', 'Ooops... Your Reset Url is invalid or expired reset token.')
                             ->withInput();
        }

        // Update password in the users table
        $hashed_password = bcrypt($new_password);
        DB::table('users')
            ->where('id', $password_reset->users_id)
            ->update(['password' => $hashed_password]);

        // Mark the reset token as used
        DB::table('password_resets')
            ->where('id', $password_reset->id)
            ->update(['used_reset_token' => 1]);

        // Set session variable to indicate successful password update
        Session::put('password_updated', true);

        // Clear other session variables
        Session::forget('reset_token');
        Session::forget('emp_no');
        Session::forget('login_id');

        // Redirect to success page
        return redirect()->route('updated_pass_result')->with('success', 'Password updated successfully.');
    }
}
