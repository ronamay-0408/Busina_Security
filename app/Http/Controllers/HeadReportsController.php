<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Unauthorized;
use App\Models\AuthorizedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HeadReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {

            /// Get today's date and the range of dates for the past week
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
                $unauthorizedCounts[] = Unauthorized::whereDate('created_at', $date)->count();
            }

            // Fetch today's and yesterday's report data
            $totalViolationsToday = Violation::whereDate('created_at', $today)->count();
            $totalViolationsYesterday = Violation::whereDate('created_at', now()->subDay()->format('Y-m-d'))->count();
            $totalUnauthorizedVehiclesToday = Unauthorized::whereDate('created_at', $today)->count();
            $totalUnauthorizedVehiclesYesterday = Unauthorized::whereDate('created_at', now()->subDay()->format('Y-m-d'))->count();

            // Fetch the count of authorized users with user_type_id = 2
            $totalAuthorizedUsers = AuthorizedUser::where('authorized_user.user_type', 2)->count();
            $totalAuthorizedUsersYesterday = AuthorizedUser::whereDate('created_at', now()->subDay()->format('Y-m-d'))->where('authorized_user.user_type', 2)->count();

            // Calculate the differences
            $violationDifference = $totalViolationsToday - $totalViolationsYesterday;
            $unauthorizedVehicleDifference = $totalUnauthorizedVehiclesToday - $totalUnauthorizedVehiclesYesterday;

            // Determine the color based on the differences
            $violationColor = $violationDifference >= 0 ? 'green' : 'red';
            $unauthorizedVehicleColor = $unauthorizedVehicleDifference >= 0 ? 'green' : 'red';

            // Return the view with the data
            return view('SSUHead.head_index', compact(
                'totalViolationsToday', 'totalViolationsYesterday', 'violationDifference',
                'totalUnauthorizedVehiclesToday', 'totalUnauthorizedVehiclesYesterday', 'unauthorizedVehicleDifference',
                'totalAuthorizedUsers', 'totalAuthorizedUsersYesterday',
                'violationColor', 'unauthorizedVehicleColor',
                'dates', 'violationCounts', 'unauthorizedCounts'
            ));
        } else {
            // abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');

            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }
}
