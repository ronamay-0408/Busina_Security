<?php

namespace App\Http\Controllers;

use App\Models\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\UnauthorizedExport; // Add this line
use Maatwebsite\Excel\Facades\Excel; // Add this line

class HeadViewUnauthorizedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Start the query builder
            $query = Unauthorized::orderBy('log_date', 'desc')->orderBy('time_in', 'desc');

            // Apply search filters if provided
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }
            if ($request->filled('year')) {
                $query->whereYear('log_date', $request->input('year'));
            }
            if ($request->filled('month')) {
                $query->whereMonth('log_date', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('log_date', $request->input('day'));
            }

            // Define the number of items per page
            $perPage = $request->input('per_page', 10);

            // Paginate results and ensure pagination maintains filters
            $unauthorizedRecords = $query->paginate($perPage)->appends($request->except('page'));

            // Check if the request is AJAX and return the partial view
            if ($request->ajax()) {
                return view('SSUHead.partials.unauthorized_table', compact('unauthorizedRecords'))->render();
            }

            // Return the main view with the paginated records
            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            return redirect()->route('index');
        }
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Start with the base query
            $query = Unauthorized::orderBy('log_date', 'desc')->orderBy('time_in', 'desc');

            // Apply search filters if provided
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }
            if ($request->filled('year')) {
                $query->whereYear('log_date', $request->input('year'));
            }
            if ($request->filled('month')) {
                $query->whereMonth('log_date', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('log_date', $request->input('day'));
            }

            // Get records for export
            $unauthorizedRecords = $query->get();

            // If no records are found, return to the previous page with an error message
            if ($unauthorizedRecords->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the applied filters.');
            }

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d'); // Format the date as desired
            $filteredexport_filename = "Filtered_Unauthorized_Report_{$currentDate}.xlsx"; // Create the filename

            // Export the filtered records to Excel using Maatwebsite Excel
            return Excel::download(new UnauthorizedExport($unauthorizedRecords), $filteredexport_filename);
        }

        return redirect()->route('index');
    }

    public function exportAllUnauthorizedExcel()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $unauthorizedRecords = Unauthorized::orderBy('log_date', 'desc')->orderBy('time_in', 'desc')->get();

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d'); // Format the date as desired
            $Allexport_filename = "All_Unauthorized_Report_{$currentDate}.xlsx"; // Create the filename

            return Excel::download(new UnauthorizedExport($unauthorizedRecords), $Allexport_filename);
        }
        return redirect()->route('index');
    }
}


