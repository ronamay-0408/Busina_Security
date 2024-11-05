<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Add this line
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Exports\AuthorizedUserExport; // Add this line
use Maatwebsite\Excel\Facades\Excel; // Add this line

class HeadViewSSUController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        $user = Auth::user();
        
        // Ensure that the user is authenticated and has the correct user type
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Fetch authorized users with user_type = 2 (Security Personnel) and join with the users table
            $authorizedUsers = DB::table('authorized_user')
                ->join('users', 'authorized_user.id', '=', 'users.authorized_user_id')
                ->select(
                    'authorized_user.fname',
                    'authorized_user.lname',
                    'authorized_user.mname',
                    'authorized_user.contact_no',
                    'users.email',
                    'authorized_user.emp_id'
                )
                ->where('authorized_user.user_type', 2) // Filtering for Security Personnel
                ->get();
            
            // Return the view with the data
            return view('SSUHead.ssu_personnel', compact('authorizedUsers'));
        } else {
            // // If the user is not authorized, return a 403 Forbidden response
            // abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');

            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }

    public function export()
    {
        // Check if the user is authenticated
        $user = Auth::user();
        
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Fetch authorized users with user_type = 2 (Security Personnel) and join with the users table
            $authorizedUsers = DB::table('authorized_user')
                ->join('users', 'authorized_user.id', '=', 'users.authorized_user_id')
                ->select(
                    'authorized_user.fname',
                    'authorized_user.lname',
                    'authorized_user.mname',
                    'authorized_user.contact_no',
                    'users.email'
                )
                ->where('authorized_user.user_type', 2) // Filtering for Security Personnel
                ->get();

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d'); // Format the date as desired
            $export_filename = "Authorized_User_Export{$currentDate}.xlsx"; // Create the filename
            
            return Excel::download(new AuthorizedUserExport($authorizedUsers), $export_filename);
        } else {
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');
        
        // Validate the request
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|integer',
        ]);

        // Check for matching employee and retrieve emp_no
        $employee = DB::table('employee')
            ->where('fname', $request->input('fname'))
            ->where('lname', $request->input('lname'))
            ->where('mname', $request->input('mname'))
            ->first();

        if ($employee) {
            $emp_no = $employee->emp_no;

            // Create a new authorized user
            $authorizedUser = DB::table('authorized_user')->insertGetId([
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'mname' => $request->input('mname'),
                'contact_no' => $request->input('contact'),
                'user_type' => $request->input('user_type'),
                'emp_id' => $employee->id,
            ]);

            // Generate a password
            $password = '@busina' . $emp_no;
            $hashed_password = Hash::make($password);

            // Create a new user record
            DB::table('users')->insert([
                'authorized_user_id' => $authorizedUser,
                'vehicle_owner_id' => $request->input('vehicle_owner_id'), // Ensure you handle vehicle_owner_id properly
                'email' => $request->input('email'),
                'password' => $hashed_password,
            ]);

            // Send email to the user
            $this->sendEmail($request->input('email'), $password, (object)[
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname')
            ]);

            // Redirect with success message
            return redirect()->route('ssu_personnel')->with('success', 'User added successfully! An email has been sent with login information.');
        } else {
            return redirect()->back()->with('error', 'This Employee is not Registered. Check the list of employees to verify the user.');
        }
    }


    /**
     * Send email using PHPMailer.
     *
     * @param  string  $email
     * @param  string  $password
     * @return void
     */
    protected function sendEmail($email, $password, $user)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'businabicoluniversity@gmail.com'; // SMTP username
            $mail->Password   = 'jpic klzq vxkd cwwc';   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;  // TCP port to connect to

            // Recipients
            $mail->setFrom('businabicoluniversity@gmail.com', 'BUsina');
            $mail->addAddress($email);  // Add recipient email

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Your Account Information for BUsina';
            $mail->Body    = "
                <html>
                <head>
                    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; font-weight: 500;'>
                    <div style='background-color: white; border-radius: 10px; width: 100%; max-width: 600px; margin: 20px auto; text-align: left;'>
                        <div style='background-color: #161a39; align-items: center; text-align: center; padding: 20px;'>
                            <h3 style='color: white; font-size: 20px;'>Welcome to BUsina</h3>
                        </div>
                        <div style='padding: 40px;'>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Hello <span style='font-weight: 600;'>{$user->fname} {$user->lname}</span>,</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Your account has been created successfully. You may now access the system.</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Here are your login details:</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Email:</strong> {$email}</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'><strong>Password:</strong> {$password}</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'>If you have any questions or need further assistance, please don't hesitate to contact us at <a href='mailto:businabicoluniversity@gmail.com' style='color: #161a39; text-decoration: none;'>busina@gmail.com</a>.</p>
                            <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Best regards,<br><span style='font-weight: 600;'>Bicol University BUsina</span></p>
                        </div>
                        <div style='background-color: #161a39; padding: 20px 20px 5px 20px;'>
                            <div style='color: #f4f4f4; font-size: 12px;'>
                                <p><span style='font-size: 14px; font-weight: 600;'>Contact</span></p>
                                <p>businabicoluniversity@gmail.com</p>
                                <p>Legazpi City, Albay, Philippines 13°08′39″N 123°43′26″E</p>
                            </div>
                            <div style='text-align: center;'>
                                <p style='color: #f4f4f4; font-size: 14px;'>Company © All Rights Reserved</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Log the error message
            Log::error("Error sending email: {$mail->ErrorInfo}");
        }
    }
}
