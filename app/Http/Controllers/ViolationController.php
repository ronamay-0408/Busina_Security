<?php

// app/Http/Controllers/ViolationController.php
namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            // Get the search term from the request
            $searchTerm = $request->input('search');

            // Set a default pagination size (e.g., 10)
            $perPage = $request->input('per_page', 10);

            // Query the violations with pagination, filtering by plate_no if a search term is provided
            $violations = Violation::when($searchTerm, function ($query, $searchTerm) {
                return $query->where('plate_no', 'like', '%' . $searchTerm . '%');
            })->paginate($perPage);

            // Pass the violations to the view
            return view('view_reports', compact('violations'));

        } else {
            // Redirect to index if not authorized
            return redirect()->route('head_index');
        }
    }

    public function show($id)
    {
        $violation = Violation::findOrFail($id);
        return view('view_per_report', compact('violation'));
    }
}
