<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\UserLogExport; // Add this line
use Maatwebsite\Excel\Facades\Excel; // Add this line

class UserLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $perPage = request('per_page', 10);
            $search = request('search', '');
            $year = request('year');
            $month = request('month');
            $day = request('day');

            // $query = UserLog::with('vehicleOwner')->orderBy('log_date', 'desc');
            $query = UserLog::with('vehicleOwner')
            ->orderBy('log_date', 'desc')         // Sort by date in descending order
            ->orderBy('time_in', 'desc');         // Then by time_in in descending order

            if ($search) {
                $query->whereHas('vehicleOwner', function ($q) use ($search) {
                    $q->where('driver_license_no', 'like', "%$search%");
                });
            }

            if ($year) {
                $query->whereYear('log_date', $year);
            }

            if ($month) {
                $query->whereMonth('log_date', $month);
            }

            if ($day) {
                $query->whereDay('log_date', $day);
            }

            $userLogs = $query->paginate($perPage);

            return view('SSUHead.head_userlogs', ['userLogs' => $userLogs]);
        } else {
            return redirect()->route('index');
        }
    }

    public function filteredExcelExport(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Start with the base query
            $query = UserLog::with('vehicleOwner')->orderBy('log_date', 'desc');

            // Apply search filters if provided
            if ($request->filled('search')) {
                $query->whereHas('vehicleOwner', function ($q) use ($request) {
                    $q->where('driver_license_no', 'like', '%' . $request->input('search') . '%');
                });
            }
            if ($request->filled('year')) {
                $query->whereYear('log_date', $request->input('year'));
            }            
            if ($request->filled('month')) {
                // Ensure the month is treated as an integer for comparison
                $month = (int) $request->input('month');
                $query->whereMonth('log_date', $month);
            }
            if ($request->filled('day')) {
                $query->whereDay('log_date', $request->input('day'));
            }

            // Get the filtered results without pagination
            $userLogs = $query->get();

            // If no records are found, return to the previous page with an error message
            if ($userLogs->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the applied filters.');
            }

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d'); // Format the date as desired
            $filteredexport_filename = "Filtered_UserLog_Report_{$currentDate}.xlsx"; // Create the filename

            // Export the filtered records to Excel
            return Excel::download(new UserLogExport($userLogs), $filteredexport_filename);
        }

        // If user is not authorized, redirect to the index page
        return redirect()->route('index');
    }

    public function allExcelExport()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $userLogs = UserLog::with('vehicleOwner')->orderBy('log_date', 'desc')->get();

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d'); // Format the date as desired
            $Allexport_filename = "All_UserLog_Report_{$currentDate}.xlsx"; // Create the filename

            return Excel::download(new UserLogExport($userLogs), $Allexport_filename);
        }
        return redirect()->route('index');
    }
}
