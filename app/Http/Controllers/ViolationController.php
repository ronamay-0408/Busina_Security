<?php

// app/Http/Controllers/ViolationController.php
namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('search');

        // Query the violations, filtering by plate_no if a search term is provided
        $violations = Violation::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('plate_no', 'like', '%' . $searchTerm . '%');
        })->get();

        // Pass the violations to the view
        return view('view_reports', compact('violations'));
    }

    public function show($id)
    {
        $violation = Violation::findOrFail($id);
        return view('view_per_report', compact('violation'));
    }
}
