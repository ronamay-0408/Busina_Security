<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // Assuming 'login.blade.php' is in 'resources/views'
    }

    public function login(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Email validation
        if (!preg_match('/@gmail\.com$/', $email) && !preg_match('/@bicol-u\.edu\.ph$/', $email)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email. Please use a valid email ending in @gmail.com or @bicol-u.edu.ph.'
            ]);
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();

            // Fetch user details including emp_id from the 'user' table
            $userDetails = DB::table('user')->where('id', $user->id)->first();

            if ($userDetails) {
                // Fetch emp_no from the 'employee' table using emp_id
                $employeeDetails = DB::table('employee')->where('id', $userDetails->emp_id)->first();

                // Check user type
                if ($userDetails->user_type == 2) {
                    // Store necessary user data in session
                    $sessionData = [
                        'id' => $userDetails->id,
                        'fname' => $userDetails->fname,
                        'lname' => $userDetails->lname,
                        'mname' => $userDetails->mname ?? '', // Handle potential null value
                        'contact_no' => $userDetails->contact_no ?? '', // Handle potential null value
                        'email' => $user->email,
                        'emp_no' => $employeeDetails->emp_no ?? '', // Handle potential null value
                    ];

                    $request->session()->put('user', $sessionData);

                    return redirect()->intended('/index');
                } else {
                    Auth::logout();
                    $request->session()->invalidate();
                    throw ValidationException::withMessages([
                        'email' => 'You do not have permission to access this page.'
                    ]);
                }
            }
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid credentials.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout the authenticated user

        $request->session()->invalidate(); // Invalidate the session

        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/'); // Redirect to the login page
    }
}
