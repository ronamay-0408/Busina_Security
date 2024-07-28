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

        // Check if the plate number already exists
        $unauthorized = Unauthorized::where('plate_no', $request->input('plate_no'))->first();

        if ($unauthorized) {
            // Update the count if the plate number exists
            if ($unauthorized->count >= 3) {
                return redirect()->back()->with('error', 'This Vehicle has been visiting Bicol University for 3 times already. If they keep visiting the University, they have to register their vehicle on BUsina.');
            }

            // Increment count by 1
            $unauthorized->increment('count');

        } else {
            // Create a new entry if the plate number does not exist
            Unauthorized::create([
                'plate_no' => $request->input('plate_no'),
                'fullname' => $request->input('fullname'),
                'purpose' => $request->input('purpose'),
                'count' => 1, // Set default count value to 1 for new entries
            ]);
        }

        // Redirect or return a response
        return redirect()->route('unauthorized')->with('success', 'Data saved successfully.');
    }
}
