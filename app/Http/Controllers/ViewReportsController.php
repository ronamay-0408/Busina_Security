<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;

class ViewReportsController extends Controller
{
    public function index()
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::user()->authorized_user_id; // Retrieve the authorized_user_id from the authenticated user

        // Fetch violation reports created by the current user
        $violations = Violation::where('reported_by', $userId)
                               ->with('violationType') // Eager load violationType for efficiency
                               ->get();

        // Pass the violations data to the view
        return view('view_reports', compact('violations'));
    }
}
