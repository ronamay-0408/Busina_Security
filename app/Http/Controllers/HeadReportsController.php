<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Unauthorized;
use App\Models\AuthorizedUser;
use App\Models\UserLog; // Import the UserLog model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class HeadReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {

            // Get today's date and the range of dates for the past week
            $today = now()->format('Y-m-d');
            $startOfWeek = now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = now()->endOfWeek()->format('Y-m-d');

            // Initialize arrays to hold the data for the past week
            $dates = [];
            $violationCounts = [];
            $unauthorizedCounts = [];

            // Loop through each day of the past week
            for ($date = $startOfWeek; $date <= $endOfWeek; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                $dates[] = $date;
                $violationCounts[] = Violation::whereDate('created_at', $date)->count();
                $unauthorizedCounts[] = Unauthorized::whereDate('log_date', $date)->count();
            }

            // Fetch today's and yesterday's report data
            $totalViolationsToday = Violation::whereDate('created_at', $today)->count();
            $totalViolationsYesterday = Violation::whereDate('created_at', now()->subDay()->format('Y-m-d'))->count();
            $totalUnauthorizedVehiclesToday = Unauthorized::whereDate('log_date', $today)->count();
            $totalUnauthorizedVehiclesYesterday = Unauthorized::whereDate('log_date', now()->subDay()->format('Y-m-d'))->count();

            // Fetch today's vehicle owner logs
            $totalVehicleOwnerLogsToday = UserLog::whereDate('log_date', $today)->count();
            $totalVehicleOwnerLogsYesterday = UserLog::whereDate('log_date', now()->subDay()->format('Y-m-d'))->count();

            // Fetch the count of authorized users with user_type_id = 2
            $totalAuthorizedUsers = AuthorizedUser::where('authorized_user.user_type', 2)->count();
            $totalAuthorizedUsersYesterday = AuthorizedUser::whereDate('created_at', now()->subDay()->format('Y-m-d'))->where('authorized_user.user_type', 2)->count();

            // Calculate the differences
            $violationDifference = $totalViolationsToday - $totalViolationsYesterday;
            $unauthorizedVehicleDifference = $totalUnauthorizedVehiclesToday - $totalUnauthorizedVehiclesYesterday;
            $vehicleOwnerLogsDifference = $totalVehicleOwnerLogsToday - $totalVehicleOwnerLogsYesterday;

            // Determine the color based on the differences
            $violationColor = $violationDifference >= 0 ? 'green' : 'red';
            $unauthorizedVehicleColor = $unauthorizedVehicleDifference >= 0 ? 'green' : 'red';

            // Initialize arrays to hold the monthly data
            $months = [];
            $monthlyViolationCounts = [];
            $monthlyUnauthorizedCounts = [];

            // Loop through each month of the current year
            for ($month = 1; $month <= 12; $month++) {
                $startOfMonth = now()->month($month)->startOfMonth()->format('Y-m-d');
                $endOfMonth = now()->month($month)->endOfMonth()->format('Y-m-d');

                $months[] = now()->month($month)->format('F'); // Get the full month name
                $monthlyViolationCounts[] = Violation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $monthlyUnauthorizedCounts[] = Unauthorized::whereBetween('log_date', [$startOfMonth, $endOfMonth])->count();
            }

            // Fetch popular violations for the current month
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year; // Get the current year
            $violations = Violation::with('violationType')
                ->selectRaw('violation_type_id, COUNT(*) as count')
                ->whereMonth('created_at', $currentMonth)
                ->groupBy('violation_type_id')
                ->orderByDesc('count')
                ->get();

            // Get the month name for display
            $currentMonthName = Carbon::now()->format('F'); // Full textual representation of a month (e.g., October)

            // Return the view with the data
            return view('SSUHead.head_index', compact(
                'totalViolationsToday', 'totalViolationsYesterday', 'violationDifference',
                'totalUnauthorizedVehiclesToday', 'totalUnauthorizedVehiclesYesterday', 'unauthorizedVehicleDifference',
                'totalVehicleOwnerLogsToday', 'vehicleOwnerLogsDifference',
                'totalAuthorizedUsers', 'totalAuthorizedUsersYesterday',
                'violationColor', 'unauthorizedVehicleColor',
                'dates', 'violationCounts', 'unauthorizedCounts',
                'months', 'monthlyViolationCounts', 'monthlyUnauthorizedCounts',
                'violations', // Pass violations data to the view
                'currentMonthName', // Pass current month name to the view
                'currentYear' // Pass current year to the view
            ));
        } else {
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }

//     public function showPopularViolations()
// {
//     $currentMonth = Carbon::now()->month;

//     // Fetch violation data grouped by violation type for the current month
//     $violations = Violation::with('violationType')
//         ->selectRaw('violation_type_id, COUNT(*) as count')
//         ->whereMonth('created_at', $currentMonth)
//         ->groupBy('violation_type_id')
//         ->orderByDesc('count')
//         ->get();

//     return view('SSUHead.head_index', compact('violations'));
// }

}
