<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

            $query = UserLog::with('vehicleOwner')->orderBy('log_date', 'desc');

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

    public function exportCsv(Request $request)
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
                $query->whereMonth('log_date', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('log_date', $request->input('day'));
            }

            // Get pagination parameters
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            // Paginate results
            $userLogs = $query->forPage($page, $perPage)->get();

            // If no records are found, return to the previous page with an error message
            if ($userLogs->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the applied filters.');
            }

            // Export the filtered records to CSV
            return $this->exportToCsv($userLogs, 'UserLogs_Filtered');
        }

        // If user is not authorized, redirect to the index page
        return redirect()->route('index');
    }

    public function exportAllCsv()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $userLogs = UserLog::with('vehicleOwner')->orderBy('log_date', 'desc')->get();
            return $this->exportToCsv($userLogs, 'UserLogs_All');
        }
        return redirect()->route('index');
    }

    private function exportToCsv($userLogs, $fileName)
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

        $columns = ['Date', 'Driver License No', 'Time In', 'Time Out'];

        $callback = function () use ($userLogs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($userLogs as $log) {
                $row = [
                    Carbon::parse($log->log_date)->format('m/d/Y'),
                    $log->vehicleOwner->driver_license_no,
                    Carbon::parse($log->time_in)->format('g:i A'),
                    $log->time_out ? Carbon::parse($log->time_out)->format('g:i A') : '',
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
