<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $lockoutTime = session('lockout_time');
        $remainingSeconds = $lockoutTime && Carbon::now()->lessThan(Carbon::parse($lockoutTime)) 
            ? Carbon::now()->diffInSeconds(Carbon::parse($lockoutTime)) 
            : 0;

        return view('login', ['remainingSeconds' => $remainingSeconds]);
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

        // Check if the user is locked out
        $lockoutTime = session('lockout_time');
        if ($lockoutTime && Carbon::now()->lessThan(Carbon::parse($lockoutTime))) {
            $remainingSeconds = Carbon::now()->diffInSeconds(Carbon::parse($lockoutTime));
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in $remainingSeconds seconds."
            ]);
        }

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

                    // Reset the login attempts on successful login
                    $request->session()->forget('login_attempts');
                    $request->session()->forget('lockout_time');

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

        // Handle failed login attempts
        $attempts = $request->session()->get('login_attempts', 0) + 1;
        $request->session()->put('login_attempts', $attempts);

        if ($attempts >= 3) {
            $lockoutTime = Carbon::now()->addSeconds(30);
            $request->session()->put('lockout_time', $lockoutTime);
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in 30 seconds.'
            ]);
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
