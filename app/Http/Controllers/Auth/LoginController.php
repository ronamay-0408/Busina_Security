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

        // Attempt to authenticate using email and password
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();

            // Fetch user details from the 'authorized_user' table
            $authorizedUserDetails = DB::table('authorized_user')->where('id', $user->authorized_user_id)->first();

            if ($authorizedUserDetails) {
                // Fetch emp_no from the 'employee' table using emp_id
                $employeeDetails = DB::table('employee')->where('id', $authorizedUserDetails->emp_id)->first();

                // Check user type and redirect accordingly
                if ($authorizedUserDetails->user_type == 1) {
                    Auth::logout();
                    $request->session()->invalidate();
                    throw ValidationException::withMessages([
                        'email' => 'You do not have permission to access this page.'
                    ]);
                } elseif ($authorizedUserDetails->user_type == 2) {
                    // Store necessary user data in session
                    $sessionData = [
                        'id' => $authorizedUserDetails->id,
                        'fname' => $authorizedUserDetails->fname,
                        'lname' => $authorizedUserDetails->lname,
                        'mname' => $authorizedUserDetails->mname ?? '',
                        'contact_no' => $authorizedUserDetails->contact_no ?? '',
                        'email' => $user->email,
                        'emp_no' => $employeeDetails->emp_no ?? '',
                    ];

                    $request->session()->put('user', $sessionData);

                    // Reset the login attempts on successful login
                    $request->session()->forget('login_attempts');
                    $request->session()->forget('lockout_time');

                    // Redirect based on user_type
                    return $this->redirectBasedOnUserType($authorizedUserDetails->user_type);
                } elseif ($authorizedUserDetails->user_type == 3) {
                    // Store necessary user data in session
                    $sessionData = [
                        'id' => $authorizedUserDetails->id,
                        'fname' => $authorizedUserDetails->fname,
                        'lname' => $authorizedUserDetails->lname,
                        'mname' => $authorizedUserDetails->mname ?? '',
                        'contact_no' => $authorizedUserDetails->contact_no ?? '',
                        'email' => $user->email,
                        'emp_no' => $employeeDetails->emp_no ?? '',
                    ];

                    $request->session()->put('user', $sessionData);

                    // Reset the login attempts on successful login
                    $request->session()->forget('login_attempts');
                    $request->session()->forget('lockout_time');

                    // Redirect to Head Security dashboard
                    return redirect()->route('head_index');
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

    // Helper method to redirect based on user_type
    protected function redirectBasedOnUserType($userType)
    {
        switch ($userType) {
            case 2:
                return redirect('/index'); // Security
            case 3:
                return redirect()->route('head_index'); // Head Security
            default:
                return redirect('/'); // Default redirect if user_type not recognized
        }
    }
}
