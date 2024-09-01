<?php

namespace App\Http\Controllers;

use App\Models\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportCsv(Request $request)
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

            // Get pagination parameters
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            // Paginate results
            $unauthorizedRecords = $query->forPage($page, $perPage)->get();

            // If no records are found, return to the previous page with an error message
            if ($unauthorizedRecords->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the applied filters.');
            }

            // Export the filtered records to CSV
            return $this->exportToCsv($unauthorizedRecords, 'Unauthorized_Report_Filtered');
        }

        // If user is not authorized, redirect to the index page
        return redirect()->route('index');
    }

    public function exportAllUnauthorizedCsv()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $unauthorizedRecords = Unauthorized::orderBy('log_date', 'desc')->orderBy('time_in', 'desc')->get();
            return $this->exportToCsv($unauthorizedRecords, 'Unauthorized_Report_All');
        }
        return redirect()->route('index');
    }

    private function exportToCsv($unauthorizedRecords, $fileName)
    {
        $date = Carbon::now()->format('Ymd_His');
        $fileName = "{$fileName}_{$date}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'Plate No', 'Time In', 'Time Out'];

        $callback = function () use ($unauthorizedRecords, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($unauthorizedRecords as $record) {
                $row = [
                    // Format the log_date to YYYY-MM-DD (you can adjust this format as needed)
                    'Date' => Carbon::parse($record->log_date)->format('m/d/Y'),
                    'Plate No' => $record->plate_no,
                    'Time In' => Carbon::parse($record->time_in)->format('g:i A'),
                    'Time Out' => $record->time_out ? Carbon::parse($record->time_out)->format('g:i A') : '',
                ];
            
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}


