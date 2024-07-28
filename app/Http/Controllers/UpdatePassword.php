<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdatePasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        // Ensure the reset token is present in the session
        if (!$request->session()->has('reset_token') || !$request->session()->has('users_id')) {
            return "Invalid or missing reset token.";
        }

        $reset_token = $request->session()->get('reset_token');
        $users_id = $request->session()->get('users_id');
        $new_password = $request->input('new_pass');
        $confirm_password = $request->input('new_pass_confirmation');

        // Validate passwords match
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
            return redirect()->back()->with('error', $error);
        }

        // Hash the new password
        $hashed_password = bcrypt($new_password);

        try {
            // Update the password in the users table
            DB::table('users')
                ->where('id', $users_id)
                ->update(['password' => $hashed_password]);

            // Check if password update was successful
            if (DB::table('users')->where('id', $users_id)->exists()) {
                // Update the used_reset_token field to indicate the token has been used
                DB::table('password_resets')
                    ->where('users_id', $users_id)
                    ->update(['used_reset_token' => 1]);

                // Clear the session variables
                $request->session()->forget('reset_token');
                $request->session()->forget('emp_no');
                $request->session()->forget('users_id');

                // Redirect to updated_pass_result.blade.php with success message
                $success = "Password updated successfully.";
                return view('updated_pass_result', compact('success'));
            } else {
                // If the password update failed, set an error message
                $error = "Failed to update password. Please try again.";
                return redirect()->back()->with('error', $error);
            }
        } catch (\Exception $e) {
            // Handle any database or other errors
            $error = "Failed to update password. Please try again later.";
            return redirect()->back()->with('error', $error);
        }
    }
}
