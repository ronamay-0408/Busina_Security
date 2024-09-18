<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unauthorized;
use Carbon\Carbon; // Import Carbon for date and time manipulation
use Illuminate\Support\Facades\Auth;

class UnauthorizedController extends Controller
{
    public function store(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            // Set the default time zone for the script
            date_default_timezone_set('Asia/Manila'); // Replace with your desired time zone

            // Validate the form input
            $request->validate([
                'plate_no' => 'required|string|max:255',
                'fullname' => 'required|string|max:255',
            ]);

            // Retrieve QR code from session
            $qrCodeData = session('qr', 'Unknown QR Code');

            // Count how many records already exist for this plate number and fullname combination
            $count = Unauthorized::where('plate_no', $request->input('plate_no'))
                                ->where('fullname', $request->input('fullname'))
                                ->count();

            if ($count >= 3) {
                // If there are already 3 records for this plate number and fullname, show an error
                return redirect()->back()->with('error', 'This Vehicle has already visited Bicol University 3 times. If they keep visiting the University, they need to register their vehicle on BUsina.');
            }

            // Create a new entry with the current date and time
            Unauthorized::create([
                'qrcode' => $qrCodeData,
                'plate_no' => $request->input('plate_no'),
                'fullname' => $request->input('fullname'),
                'log_date' => Carbon::now()->toDateString(), // Current date
                'time_in' => Carbon::now()->toTimeString(), // Current time
            ]);

            // Redirect or return a response
            return redirect()->route('visitor_scanner')->with('success', 'Data saved successfully.');

        } else {
            // Redirect to index if not authorized
            // abort(403, 'Unauthorized action.');
            return redirect()->route('head_index');
        }
    }
}
