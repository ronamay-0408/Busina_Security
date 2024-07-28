<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unauthorized;

class UnauthorizedController extends Controller
{
    public function store(Request $request)
    {
        // Set the default time zone for the script
        date_default_timezone_set('Asia/Manila'); // Replace with your desired time zone

        // Validate the form input
        $request->validate([
            'plate_no' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        // Count how many records already exist for this plate number
        $count = Unauthorized::where('plate_no', $request->input('plate_no'))->count();

        if ($count >= 3) {
            // If there are already 3 records for this plate number, show an error
            return redirect()->back()->with('error', 'This Vehicle has already visited Bicol University 3 times. If they keep visiting the University, they need to register their vehicle on BUsina.');
        }

        // Create a new entry since there are fewer than 3 existing records
        Unauthorized::create([
            'plate_no' => $request->input('plate_no'),
            'fullname' => $request->input('fullname'),
            'purpose' => $request->input('purpose'),
        ]);

        // Redirect or return a response
        return redirect()->route('unauthorized')->with('success', 'Data saved successfully.');
    }
}
